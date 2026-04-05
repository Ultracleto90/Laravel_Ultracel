<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PortalClienteController extends Controller
{
    public function rastrear(Request $request)
    {
        $request->validate([
            'reparacion_id' => 'required|integer'
        ]);

        $detalles = DB::table('reparaciones as r')
            // 🔥 CORRECCIÓN: Usamos id_equipo en lugar de equipo_id
            ->join('equipos as e', 'r.id_equipo', '=', 'e.id_equipo')
            // 🔥 CORRECCIÓN: Usamos id_cliente en lugar de cliente_id
            ->join('clientes as c', 'e.id_cliente', '=', 'c.id_cliente')
            ->where('r.id_reparacion', $request->reparacion_id)
            ->select(
                'r.id_reparacion as id', 
                'r.taller_id', 
                'e.marca',
                'e.modelo',
                'c.nombre as cliente_nombre',
                'r.estado',
                'r.problema_reportado as falla_reportada', 
                'r.diagnostico_tecnico',
                'r.presupuesto'
            )
            ->first();

        if (!$detalles) {
            return response()->json(['status' => false, 'message' => 'Folio no encontrado. Verifica tu ticket.'], 404);
        }

        return response()->json(['status' => true, 'detalles' => $detalles]);
    }

    public function consultarTicket(Request $request)
    {
        $request->validate([
            'id_reparacion' => 'required|integer' 
        ]);

        $ticket = DB::table('reparaciones as r')
            // 🔥 CORRECCIÓN: Usamos id_equipo en lugar de equipo_id
            ->join('equipos as e', 'r.id_equipo', '=', 'e.id_equipo')
            // 🔥 CORRECCIÓN: Usamos id_cliente en lugar de cliente_id
            ->join('clientes as c', 'e.id_cliente', '=', 'c.id_cliente')
            ->join('talleres as t', 'r.taller_id', '=', 't.id') 
            ->where('r.id_reparacion', $request->id_reparacion) 
            ->select(
                'r.id_reparacion as folio',
                'r.taller_id',
                't.nombre_negocio as sucursal_asignada', 
                'e.marca',
                'e.modelo',
                'c.nombre as nombre_cliente',
                'r.estado as estado_reparacion',
                'r.problema_reportado as falla_reportada', 
                'r.diagnostico_tecnico',
                'r.presupuesto',
                DB::raw("DATE_FORMAT(r.created_at, '%Y-%m-%d') as fecha_ingreso")
            )
            ->first();

        if (!$ticket) {
            return response()->json([
                'status' => false,
                'message' => 'El número de folio ingresado no existe. Por favor verifica tu ticket.'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'ticket' => $ticket
        ], 200);
    }

    public function rastreoMovil($folio)
    {
        $ticket = \Illuminate\Support\Facades\DB::table('reparaciones')
            ->join('equipos', 'reparaciones.equipo_id', '=', 'equipos.id')
            ->join('clientes', 'equipos.cliente_id', '=', 'clientes.id')
            ->where('reparaciones.id', $folio)
            ->select(
                'reparaciones.id as folio', 
                'reparaciones.estado', 
                'clientes.nombre as cliente', 
                'equipos.modelo', 
                'reparaciones.falla_reportada as falla', 
                'reparaciones.costo_estimado as cotizacion'
            )
            ->first();

        if (!$ticket) {
            return response()->json(['status' => false, 'message' => 'Folio no encontrado'], 404);
        }

        return response()->json($ticket, 200);
    }
}