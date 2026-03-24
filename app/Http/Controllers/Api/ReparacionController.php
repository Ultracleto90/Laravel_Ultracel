<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // <-- ¡Este es vital para hacer las consultas!

class ReparacionController extends Controller
{
    // 1. Función para listar las reparaciones pendientes del taller
    public function pendientes(Request $request)
    {
        // Traemos todas las reparaciones del taller que NO hayan sido entregadas al cliente
        $reparaciones = DB::table('reparaciones')
            ->join('equipos', 'reparaciones.id_equipo', '=', 'equipos.id_equipo')
            ->where('reparaciones.taller_id', $request->taller_id)
            ->whereNotIn('reparaciones.estado', ['Entregado']) // Solo ocultamos los que ya se fueron
            ->select(
                'reparaciones.id_reparacion',
                DB::raw("CONCAT(equipos.marca, ' ', equipos.modelo) as dispositivo"),
                'reparaciones.estado', // <-- ¡El campo clave para los colores!
                'reparaciones.problema_reportado'
            )
            ->orderBy('reparaciones.fecha_recepcion', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'reparaciones' => $reparaciones
        ]);
    }

    // 2. Obtener todos los detalles de una reparación específica para el Modal
    public function detalles(Request $request)
    {
        $detalles = DB::table('reparaciones as r')
            ->join('equipos as e', 'r.id_equipo', '=', 'e.id_equipo')
            ->join('clientes as c', 'e.id_cliente', '=', 'c.id_cliente')
            ->leftJoin('users as u', 'r.id_tecnico_asignado', '=', 'u.id')
            ->where('r.id_reparacion', $request->id_reparacion)
            ->select(
                'e.marca', 'e.modelo', 'e.tipo_equipo', 'e.imei_o_serie', 'e.clave_acceso',
                'c.nombre', 'c.apellidos', 'c.telefono',
                'r.problema_reportado', 'r.estado',
                'u.name as tecnico_asignado'
            )
            ->first();

        return response()->json(['status' => true, 'detalles' => $detalles]);
    }

    // 3. El botón para marcar la reparación como terminada
    public function terminar(Request $request)
    {
        DB::table('reparaciones')->where('id_reparacion', $request->id_reparacion)
            ->update(['estado' => 'Reparado', 'fecha_entrega_real' => now()]);

        return response()->json(['status' => true]);
    }
}