<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReparacionController extends Controller
{
    // Función para listar las reparaciones pendientes del taller
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

    public function detalles(Request $request)
    {
        // Exigimos que la app nos mande el 'id_reparacion' (el folio)
        $request->validate([
            'id_reparacion' => 'required|integer'
        ]);

        $reparacion = DB::table('reparaciones as r')
            ->join('equipos as e', 'r.id_equipo', '=', 'e.id_equipo')
            ->where('r.id_reparacion', $request->id_reparacion)
            ->select(
                'r.id_reparacion', 
                'r.estado', 
                'r.problema_reportado',
                'r.costo_estimado', // Cambia esto si tu columna de precio se llama diferente
                DB::raw("CONCAT(e.marca, ' ', e.modelo) AS dispositivo")
            )
            ->first();

        if (!$reparacion) {
            return response()->json([
                'status' => false,
                'message' => 'No encontramos ninguna reparación con ese folio.'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $reparacion
        ], 200);
    }
}