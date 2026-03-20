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
        $request->validate([
            'taller_id' => 'required|integer'
        ]);

        $reparaciones = DB::table('reparaciones as r')
            ->join('equipos as e', 'r.id_equipo', '=', 'e.id_equipo')
            ->where('r.taller_id', $request->taller_id)
            ->whereIn('r.estado', ['Recibido', 'En Diagnóstico'])
            ->select('r.id_reparacion', DB::raw("CONCAT(e.marca, ' ', e.modelo) AS dispositivo"), 'r.problema_reportado')
            ->orderBy('r.fecha_recepcion', 'asc')
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