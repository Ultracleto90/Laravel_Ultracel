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
    /**
     * GET: Devuelve los datos básicos de la reparación para la App Móvil (Lalo/Ian)
     */
    public function obtenerDetalle($id_reparacion)
    {
        $reparacion = \Illuminate\Support\Facades\DB::table('reparaciones')
            ->join('equipos', 'reparaciones.id_equipo', '=', 'equipos.id_equipo')
            ->join('clientes', 'equipos.id_cliente', '=', 'clientes.id_cliente')
            ->where('reparaciones.id_reparacion', $id_reparacion)
            ->select(
                'equipos.modelo',
                'reparaciones.presupuesto as costo',
                \Illuminate\Support\Facades\DB::raw("CONCAT(clientes.nombre, ' ', clientes.apellidos) as cliente")
            )
            ->first();

        if (!$reparacion) {
            return response()->json(['error' => 'Reparación no encontrada'], 404);
        }

        // Formateamos la respuesta exactamente como la pide el JSON de la App
        return response()->json([
            'modelo' => $reparacion->modelo,
            'costo' => (float) $reparacion->costo, // Lo casteamos a float por seguridad
            'cliente' => trim($reparacion->cliente)
        ]);
    }

    /**
     * POST: Actualiza el estado de la reparación desde la App Móvil
     */
    public function actualizarEstado(\Illuminate\Http\Request $request)
    {
        // 1. Validamos que lleguen los datos y que el estado sea uno de los 4 permitidos
        $request->validate([
            'id_reparacion' => 'required|integer',
            'estado' => 'required|string|in:En Diagnóstico,En Reparación,Listo,Entregado'
        ]);

        // 2. Actualizamos la base de datos
        $actualizado = \Illuminate\Support\Facades\DB::table('reparaciones')
            ->where('id_reparacion', $request->id_reparacion)
            ->update([
                'estado' => $request->estado,
                'updated_at' => now()
            ]);

        if (!$actualizado) {
            return response()->json(['error' => 'No se pudo actualizar o la reparación no existe'], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Estado actualizado correctamente a: ' . $request->estado
        ]);
    }
}