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
}