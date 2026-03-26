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
            ->join('equipos', 'reparaciones.equipo_id', '=', 'equipos.id')
            ->where('reparaciones.taller_id', $request->taller_id)
            ->whereNotIn('reparaciones.estado', ['entregado', 'cancelado']) // Usando los valores ENUM correctos de tu DB
            ->select(
                'reparaciones.id', // Llave primaria de la reparación
                DB::raw("CONCAT(equipos.marca, ' ', equipos.modelo) as dispositivo"),
                'reparaciones.estado',
                'reparaciones.falla_reportada' // Ajustado al nombre real de la columna
            )
            ->orderBy('reparaciones.created_at', 'desc') // Usamos created_at porque fecha_recepcion no existe en tu esquema
            ->get();

        return response()->json([
            'status' => true,
            'reparaciones' => $reparaciones
        ]);
    }

    // 2. Obtener todos los detalles de una reparación específica para el Modal
    // 2. Obtener todos los detalles de una reparación específica para el Modal y Ticket
    public function detalles(Request $request)
    {
        $detalles = DB::table('reparaciones as r')
            ->join('equipos as e', 'r.equipo_id', '=', 'e.id')
            ->join('clientes as c', 'e.cliente_id', '=', 'c.id')
            ->leftJoin('users as u', 'r.tecnico_id', '=', 'u.id')
            ->where('r.id', $request->reparacion_id) // Asumiendo que Python manda ?reparacion_id=X
            ->select(
                'r.id', // <--- ¡Faltaba este para el número de folio del ticket!
                'r.taller_id', // <--- ¡El pase VIP para la tiendita!
                'e.marca', 
                'e.modelo', 
                'e.tipo', 
                'e.imei_serie', 
                'e.contrasena_desbloqueo', 
                'c.nombre as cliente_nombre', 
                'c.telefono',
                'r.falla_reportada', 
                'r.estado',
                'r.diagnostico_tecnico', 
                'u.name as tecnico_asignado'
            )
            ->first();

        if (!$detalles) {
            return response()->json(['status' => false, 'message' => 'Reparación no encontrada'], 404);
        }

        return response()->json(['status' => true, 'detalles' => $detalles]);
    }

    // 3. El botón para marcar la reparación como terminada
    public function terminar(Request $request)
    {
        $actualizado = DB::table('reparaciones')
            ->where('id', $request->reparacion_id)
            ->update([
                'estado' => 'listo', // Tu ENUM no tiene 'Reparado', tiene 'listo'
                'fecha_entrega_real' => now()
            ]);

        if ($actualizado) {
            return response()->json(['status' => true, 'message' => 'Equipo marcado como listo para entrega.']);
        }

        return response()->json(['status' => false, 'message' => 'No se pudo actualizar la reparación.'], 400);
    }
}