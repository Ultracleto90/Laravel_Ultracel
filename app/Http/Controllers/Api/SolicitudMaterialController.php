<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SolicitudMaterialController extends Controller
{
    // 1. Obtener el historial de solicitudes de este técnico
    public function listar(Request $request)
    {
        $request->validate([
            'id_tecnico' => 'required|integer'
        ]);

        $solicitudes = DB::table('solicitudes_material')
            ->where('id_tecnico_solicitante', $request->id_tecnico)
            ->select(
                DB::raw("DATE_FORMAT(fecha_solicitud, '%Y-%m-%d') as fecha"),
                'nombre_producto',
                'cantidad_solicitada',
                'estado_solicitud'
            )
            ->orderBy('fecha_solicitud', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'solicitudes' => $solicitudes
        ]);
    }

    // 2. Crear una nueva solicitud
    public function crear(Request $request)
    {
        $request->validate([
            'id_tecnico' => 'required|integer',
            'taller_id' => 'required|integer',
            'nombre_producto' => 'required|string',
            'cantidad' => 'required|integer'
        ]);

        DB::table('solicitudes_material')->insert([
            'id_tecnico_solicitante' => $request->id_tecnico,
            'taller_id' => $request->taller_id,
            'nombre_producto' => $request->nombre_producto,
            'cantidad_solicitada' => $request->cantidad,
            'descripcion' => $request->descripcion,
            'estado_solicitud' => 'Pendiente',
            'fecha_solicitud' => now()
        ]);

        return response()->json(['status' => true, 'message' => 'Solicitud enviada.']);
    }
    // 3. Listar TODAS las solicitudes del taller para el Administrador
    public function listarAdmin(Request $request)
    {
        $request->validate(['taller_id' => 'required|integer']);

        $solicitudes = DB::table('solicitudes_material as s')
            ->join('users as u', 's.id_tecnico_solicitante', '=', 'u.id')
            ->where('s.taller_id', $request->taller_id)
            ->select(
                's.id_solicitud',
                DB::raw("DATE_FORMAT(s.fecha_solicitud, '%Y-%m-%d') as fecha"),
                'u.name as nombre_completo',
                's.nombre_producto',
                's.cantidad_solicitada',
                's.estado_solicitud'
            )
            ->orderBy('s.fecha_solicitud', 'desc')
            ->get();

        return response()->json(['status' => true, 'solicitudes' => $solicitudes]);
    }

    // 4. Actualizar el estado de la solicitud (Aprobar/Rechazar)
    public function actualizarEstado(Request $request)
    {
        $request->validate([
            'id_solicitud' => 'required|integer',
            'estado' => 'required|string'
        ]);

        DB::table('solicitudes_material')
            ->where('id_solicitud', $request->id_solicitud)
            ->update(['estado_solicitud' => $request->estado]);

        return response()->json(['status' => true, 'message' => 'Estado actualizado.']);
    }
}