<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 

class ReparacionController extends Controller
{
    // 1. Función para listar las reparaciones pendientes del taller
    public function pendientes(Request $request)
    {
        // Traemos todas las reparaciones del taller que NO hayan sido entregadas al cliente
        $reparaciones = DB::table('reparaciones')
            // 🐛 Corregimos los nombres de las columnas en el JOIN
            ->join('equipos', 'reparaciones.id_equipo', '=', 'equipos.id_equipo') 
            ->where('reparaciones.taller_id', $request->taller_id)// 🔒 CANDADO OK (Ya lo tenías)
            ->whereNotIn('reparaciones.estado', ['entregado', 'cancelado']) // Usando los valores ENUM correctos de tu DB
            ->select(
                'reparaciones.id_reparacion', 
                DB::raw("CONCAT(equipos.marca, ' ', equipos.modelo) as dispositivo"),
                'reparaciones.estado',
                'reparaciones.problema_reportado' // 🔙 Revertido a tu nombre original de BD
            )
            ->orderBy('reparaciones.created_at', 'desc') // Usamos created_at porque fecha_recepcion no existe en tu esquema
            ->get();

        return response()->json([
            'status' => true,
            'reparaciones' => $reparaciones
        ]);
    }

    // 2. Obtener todos los detalles de una reparación específica para el Modal y Ticket
    public function detalles(Request $request)
    {
        $detalles = DB::table('reparaciones as r')
            // 🐛 Corregimos el JOIN de equipos
            ->join('equipos as e', 'r.id_equipo', '=', 'e.id_equipo') 
            // Si la tabla clientes también usa "id_cliente" en lugar de "id", cámbialo así:
            ->join('clientes as c', 'e.id_cliente', '=', 'c.id_cliente')
            ->leftJoin('users as u', 'r.tecnico_id', '=', 'u.id')
            ->where('r.taller_id', $request->taller_id) // 🔒 CANDADO DE SEGURIDAD AÑADIDO (Protege datos del cliente y contraseñas)
            ->where('r.id_reparacion', $request->reparacion_id) // 🐛 Corregido
            ->select(
                'r.id_reparacion', // 🐛 Corregido// <--- ¡Faltaba este para el número de folio del ticket!
                'r.taller_id', // <--- ¡El pase VIP para la tiendita!
                'e.marca', 
                'e.modelo', 
                'e.tipo', 
                'e.imei_serie', 
                'e.contrasena_desbloqueo', 
                'c.nombre as cliente_nombre', 
                'c.telefono',
                'r.problema_reportado', // 🔙 Revertido a tu nombre original de BD
                'r.estado',
                'r.diagnostico_tecnico', 
                'u.name as tecnico_asignado'
            )
            ->first();

        if (!$detalles) {
            return response()->json(['status' => false, 'message' => 'Reparación no encontrada o no pertenece a tu taller.'], 404);
        }

        return response()->json(['status' => true, 'detalles' => $detalles]);
    }

    // 3. El botón para marcar la reparación como terminada
    public function terminar(Request $request)
    {
        $actualizado = DB::table('reparaciones')
            ->where('taller_id', $request->taller_id) 
            ->where('id_reparacion', $request->reparacion_id) // 🐛 Corregido
            ->update([
                'estado' => 'listo', // Tu ENUM no tiene 'Reparado', tiene 'listo'
                'fecha_entrega_real' => now()
            ]);

        if ($actualizado) {
            return response()->json(['status' => true, 'message' => 'Equipo marcado como listo para entrega.']);
        }

        return response()->json(['status' => false, 'message' => 'No se pudo actualizar la reparación o no pertenece a este taller.'], 400);
    }

    // 4. El motor del Kanban: Actualizar estado al arrastrar tarjetas en React Native
    public function updateStatus(Request $request)
    {
        // 1. Validamos que lleguen los datos requeridos
        $request->validate([
            'reparacion_id' => 'required|integer',
            'estado' => 'required|string',
            'taller_id' => 'required|integer'
        ]);

        // 2. Ejecutamos el cambio de estado con el candado de seguridad
        $actualizado = DB::table('reparaciones')
            ->where('id_reparacion', $request->reparacion_id) // 🐛 Corregido
            ->where('taller_id', $request->taller_id) // 🔒 CANDADO VITAL
            ->update([
                'estado' => $request->estado,
                'updated_at' => now()
            ]);

        // 3. Respuesta para la app móvil
        if ($actualizado) {
            // Tip CTO: Aquí en el futuro inyectaremos la API de WhatsApp para avisarle al cliente
            return response()->json([
                'status' => true, 
                'message' => '¡Tarjeta movida a ' . strtoupper($request->estado) . ' exitosamente!'
            ], 200);
        }

        return response()->json([
            'status' => false, 
            'message' => 'No se pudo mover la tarjeta. Verifica los permisos de tu taller.'
        ], 400);
    }

    public function pendientesMovil($tallerId)
    {
        $reparaciones = \Illuminate\Support\Facades\DB::table('reparaciones')
            // 🐛 CORREGIDO: Nombres exactos de tus llaves
            ->join('equipos', 'reparaciones.id_equipo', '=', 'equipos.id_equipo')
            ->where('reparaciones.taller_id', $tallerId)
            ->whereNotIn('reparaciones.estado', ['Entregado', 'Cancelado'])
            // 🐛 CORREGIDO: id_reparacion en lugar de id
            ->select('reparaciones.id_reparacion as folio', 'equipos.modelo', 'reparaciones.estado')
            ->get();

        return response()->json($reparaciones, 200);
    }

    public function nuevaRecepcion(\Illuminate\Http\Request $request)
    {
        // 1. Validamos lo que manda Lalo
        $request->validate([
            'cliente' => 'required|string',
            'telefono' => 'required|string',
            'modelo' => 'required|string',
            'falla' => 'required|string',
            'cotizacion' => 'required|numeric'
        ]);

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // Creamos un cliente express (o lo buscas si ya existe en tu lógica completa)
            // 1. Creamos el cliente express
            $clienteId = \Illuminate\Support\Facades\DB::table('clientes')->insertGetId([
                'nombre' => $request->cliente,
                'apellidos' => '', // El parche que pusimos hace rato
                'telefono' => $request->telefono,
                'taller_id' => $request->taller_id ?? 1, 
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // 2. Creamos el equipo
            $equipoId = \Illuminate\Support\Facades\DB::table('equipos')->insertGetId([
                'id_cliente' => $clienteId, 
                'modelo' => $request->modelo,
                'tipo_equipo' => 'Celular', // 🐛 CORREGIDO: Tu tabla dice tipo_equipo, no tipo
                'marca' => 'Generica', // 🐛 AÑADIDO: Tu tabla exige una marca, le ponemos esta por defecto
                'created_at' => now(),
                'updated_at' => now()
            ]);

        
            // 3. Registramos la reparación
            \Illuminate\Support\Facades\DB::table('reparaciones')->insert([
                'taller_id' => $request->taller_id ?? 1,
                'id_equipo' => $equipoId, 
                'estado' => 'Recibido', // 🐛 CORREGIDO: Con mayúscula como tu ENUM
                'problema_reportado' => $request->falla, 
                'presupuesto' => $request->cotizacion, // 🐛 CORREGIDO: Tu tabla dice presupuesto, no costo_estimado
                'created_at' => now(),
                'updated_at' => now()
            ]);

            \Illuminate\Support\Facades\DB::commit();
            return response()->json(['status' => true, 'message' => 'Reparación guardada con éxito'], 201);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json(['status' => false, 'message' => 'Error al guardar: ' . $e->getMessage()], 500);
        }
    }
    
}