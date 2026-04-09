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
        // 3. Si alguien intenta adivinar el PIN y falla (MODO DEBUG ACTIVADO)
        if (!$reparacion) {
            return response()->json([
                'status' => false, 
                'message' => 'Folio o PIN incorrectos. Verifica tu ticket de servicio.',
                'debug_folio_que_recibio_el_servidor' => $request->folio,
                'debug_pin_que_recibio_el_servidor' => $request->pin,
                'debug_tipo_de_dato_folio' => gettype($request->folio),
                'debug_tipo_de_dato_pin' => gettype($request->pin)
            ], 404);
        }

        // 4. Si es correcto, le mandamos a Lalo todos los datos para que arme una pantalla insana
        return response()->json([
            'status' => true,
            'datos' => $reparacion
        ], 200);
    }

    // 📱 EL MOTOR DEL ESCÁNER MÓVIL (Recibiendo datos de Lalo)
    public function entregarPorQR(Request $request)
    {
        // 1. Le pedimos a Lalo que nos mande todo
        $request->validate([
            'folio' => 'required',
            'taller_id' => 'required|integer',
            'id_vendedor' => 'required|integer' // Necesitamos saber qué vendedor cobró para la gráfica
        ]);

        // Limpiamos el folio (Si Lalo manda "REP-15", sacamos solo el "15")
        $id_reparacion = preg_replace('/[^0-9]/', '', $request->folio);

        // Buscamos con el Taller ID que mandó Lalo
        $reparacion = DB::table('reparaciones')
            ->where('id_reparacion', $id_reparacion)
            ->where('taller_id', $request->taller_id) 
            ->first();

        if (!$reparacion) {
            return response()->json(['status' => false, 'message' => 'Folio no encontrado en esta sucursal.'], 404);
        }

        // 🛡️ EL ESCUDO ANTI-CHOQUE
        if ($reparacion->estado === 'Entregado') {
            return response()->json(['status' => false, 'message' => 'Este equipo ya fue entregado y cobrado en caja'], 400);
        }

        if ($reparacion->estado !== 'Reparado') {
            return response()->json(['status' => false, 'message' => 'El equipo aún no está Listo para entrega. Estado actual: ' . $reparacion->estado], 400);
        }

        DB::beginTransaction();
        try {
            // 3. Pasamos el equipo a Entregado
            DB::table('reparaciones')
                ->where('id_reparacion', $id_reparacion)
                ->update([
                    'estado' => 'Entregado',
                    'fecha_entrega_real' => now()
                ]);

            // 📈 4. EL GATILLO FINANCIERO 
            $costo_piezas = DB::table('reparacion_piezas')
                ->join('inventario', 'reparacion_piezas.id_producto', '=', 'inventario.id_producto')
                ->where('reparacion_piezas.id_reparacion', $id_reparacion)
                ->sum(DB::raw('reparacion_piezas.cantidad_usada * inventario.precio_compra'));

            $monto_total = $reparacion->presupuesto;
            $utilidad_neta = $monto_total - $costo_piezas;

            // Creamos el ticket con los datos que mandó Lalo
            $id_venta = DB::table('ventas')->insertGetId([
                'taller_id' => $request->taller_id,
                'id_cliente' => DB::table('equipos')->where('id_equipo', $reparacion->id_equipo)->value('id_cliente'),
                'id_vendedor' => $request->id_vendedor, 
                'monto_total' => $monto_total,
                'utilidad_neta' => $utilidad_neta,
                'fecha_venta' => now(),
                'metodo_pago' => 'Efectivo (App Móvil)'
            ]);

            DB::table('venta_detalles')->insert([
                'id_venta' => $id_venta,
                'id_reparacion' => $id_reparacion,
                'cantidad' => 1,
                'precio_unitario' => $monto_total,
                'descripcion_linea' => 'Cobro y Entrega vía Escáner QR (Folio: ' . $id_reparacion . ')'
            ]);

            DB::commit();
            
            return response()->json([
                'status' => true,
                'message' => '¡Boom! Equipo entregado y cobrado exitosamente.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'Error interno: ' . $e->getMessage()], 500);
        }
    }
}