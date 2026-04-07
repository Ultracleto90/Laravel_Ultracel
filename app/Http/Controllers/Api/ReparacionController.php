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
        $reparaciones = DB::table('reparaciones')
            ->join('equipos', 'reparaciones.id_equipo', '=', 'equipos.id_equipo') 
            ->where('reparaciones.taller_id', $request->taller_id)// 🔒 CANDADO OK
            ->whereNotIn('reparaciones.estado', ['Entregado', 'Cancelado']) 
            ->select(
                'reparaciones.id_reparacion', 
                DB::raw("CONCAT(equipos.marca, ' ', equipos.modelo) as dispositivo"),
                'reparaciones.estado',
                'reparaciones.problema_reportado' 
            )
            ->orderBy('reparaciones.fecha_recepcion', 'desc') // 🐛 CORREGIDO: Usamos la columna real de tu migración
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
            ->join('equipos as e', 'r.id_equipo', '=', 'e.id_equipo') 
            ->join('clientes as c', 'e.id_cliente', '=', 'c.id_cliente')
            ->leftJoin('users as u', 'r.id_tecnico_asignado', '=', 'u.id') // 🐛 CORREGIDO: Nombre real de la columna
            ->where('r.taller_id', $request->taller_id) // 🔒 CANDADO DE SEGURIDAD 
            ->where('r.id_reparacion', $request->reparacion_id) 
            ->select(
                'r.id_reparacion', 
                'r.taller_id', 
                'e.marca', 
                'e.modelo', 
                'e.tipo_equipo', // 🐛 CORREGIDO
                'e.imei_o_serie', // 🐛 CORREGIDO
                'e.clave_acceso', // 🐛 CORREGIDO
                'c.nombre as cliente_nombre', 
                'c.telefono',
                'r.problema_reportado', 
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
            ->where('id_reparacion', $request->reparacion_id) 
            ->update([
                'estado' => 'Reparado', // 🐛 CORREGIDO: Ajustado a tu ENUM ('Reparado', no 'listo')
                'fecha_entrega_real' => now()
            ]);

        if ($actualizado) {
            return response()->json(['status' => true, 'message' => 'Equipo marcado como reparado.']);
        }

        return response()->json(['status' => false, 'message' => 'No se pudo actualizar la reparación o no pertenece a este taller.'], 400);
    }

    // 4. El motor del Kanban: Actualizar estado al arrastrar tarjetas
    public function updateStatus(Request $request)
    {
        $request->validate([
            'reparacion_id' => 'required|integer',
            'estado' => 'required|string',
            'taller_id' => 'required|integer'
        ]);

        $actualizado = DB::table('reparaciones')
            ->where('id_reparacion', $request->reparacion_id) 
            ->where('taller_id', $request->taller_id) // 🔒 CANDADO VITAL
            ->update([
                'estado' => $request->estado,
                'updated_at' => now()
            ]);

        if ($actualizado) {
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
        $reparaciones = DB::table('reparaciones')
            ->join('equipos', 'reparaciones.id_equipo', '=', 'equipos.id_equipo')
            ->where('reparaciones.taller_id', $tallerId)
            ->whereNotIn('reparaciones.estado', ['Entregado', 'Cancelado'])
            ->select('reparaciones.id_reparacion as folio', 'equipos.modelo', 'reparaciones.estado')
            ->get();

        return response()->json($reparaciones, 200);
    }

    public function nuevaRecepcion(Request $request)
    {
        $request->validate([
            'cliente' => 'required|string',
            'telefono' => 'required|string',
            'modelo' => 'required|string',
            'falla' => 'required|string',
            'cotizacion' => 'required|numeric'
        ]);

        DB::beginTransaction();
        try {
            $clienteId = DB::table('clientes')->insertGetId([
                'nombre' => $request->cliente,
                'apellidos' => '', 
                'telefono' => $request->telefono,
                'taller_id' => $request->taller_id ?? 1, 
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $equipoId = DB::table('equipos')->insertGetId([
                'id_cliente' => $clienteId, 
                'modelo' => $request->modelo,
                'tipo_equipo' => 'Celular', 
                'marca' => 'Generica', 
                'created_at' => now(),
                'updated_at' => now()
            ]);
        
            // 🔐 GENERADOR DE PIN SEGURO (4 DÍGITOS)
            $pinAleatorio = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);

            // Cambiamos insert por insertGetId para atrapar el folio
            $id_reparacion = DB::table('reparaciones')->insertGetId([
                'taller_id' => $request->taller_id ?? 1,
                'id_equipo' => $equipoId, 
                'estado' => 'Recibido', 
                'problema_reportado' => $request->falla, 
                'presupuesto' => $request->cotizacion, 
                'pin_cliente' => $pinAleatorio, // <-- EL CANDADO APLICADO
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();
            
            // Le regresamos el folio y pin a Lalo para que los muestre en la app
            return response()->json([
                'status' => true, 
                'message' => 'Reparación guardada con éxito',
                'folio' => $id_reparacion,
                'pin' => $pinAleatorio
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'Error al guardar: ' . $e->getMessage()], 500);
        }
    }

    // =========================================================
    // 📱 APP CLIENTE: RASTREO CON FOLIO Y PIN
    // =========================================================
    public function rastrearReparacionMovil(Request $request)
    {
        // 1. Lalo nos tiene que mandar el folio y el pin
        $request->validate([
            'folio' => 'required|integer',
            'pin' => 'required|string'
        ]);

        // 2. Buscamos el match perfecto en la base de datos
        $reparacion = DB::table('reparaciones')
            ->join('equipos', 'reparaciones.id_equipo', '=', 'equipos.id_equipo')
            ->join('talleres', 'reparaciones.taller_id', '=', 'talleres.id')
            ->where('reparaciones.id_reparacion', $request->folio)
            ->where('reparaciones.pin_cliente', $request->pin) // 🔒 EL CANDADO DE LALO
            ->select(
                'reparaciones.id_reparacion as folio',
                'reparaciones.estado',
                'reparaciones.problema_reportado',
                'reparaciones.diagnostico_tecnico',
                'reparaciones.presupuesto',
                'equipos.marca',
                'equipos.modelo',
                'talleres.nombre_negocio as sucursal'
            )
            ->first();

        // 3. Si alguien intenta adivinar el PIN y falla:
        if (!$reparacion) {
            return response()->json([
                'status' => false, 
                'message' => 'Folio o PIN incorrectos. Verifica tu ticket de servicio.'
            ], 404);
        }

        // 4. Si es correcto, le mandamos a Lalo todos los datos para que arme una pantalla insana
        return response()->json([
            'status' => true,
            'datos' => $reparacion
        ], 200);
    }
}