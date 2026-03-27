<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PortalClienteController extends Controller
{
    // Función exclusiva para que la App del Cliente rastree su equipo
    public function rastrear(Request $request)
    {
        $request->validate([
            'reparacion_id' => 'required|integer'
        ]);

        $detalles = DB::table('reparaciones as r')
            ->join('equipos as e', 'r.equipo_id', '=', 'e.id')
            ->join('clientes as c', 'e.cliente_id', '=', 'c.id')
            ->where('r.id_reparacion', $request->reparacion_id)
            ->select(
                'r.id_reparacion as id', 
                'r.taller_id', 
                'e.marca',
                'e.modelo',
                'c.nombre as cliente_nombre',
                'r.estado',
                'r.falla_reportada',
                'r.diagnostico_tecnico',
                'r.presupuesto'
            )
            ->first();

        if (!$detalles) {
            return response()->json(['status' => false, 'message' => 'Folio no encontrado. Verifica tu ticket.'], 404);
        }

        return response()->json(['status' => true, 'detalles' => $detalles]);
    }

    // Función para el Ticket: consultarTicket (LA QUE USA LA APP MÓVIL)
    public function consultarTicket(Request $request)
    {
        // 1. Validamos que la app nos mande el folio en el JSON
        $request->validate([
            'id_reparacion' => 'required|integer' 
        ]);

        // 2. Buscamos el folio en toda la base de datos (SIN filtro de taller_id)
        $ticket = DB::table('reparaciones as r')
            ->join('equipos as e', 'r.equipo_id', '=', 'e.id')
            ->join('clientes as c', 'e.cliente_id', '=', 'c.id')
            ->join('talleres as t', 'r.taller_id', '=', 't.id') 
            // 🔥 CORRECCIÓN: Usamos id_reparacion para el WHERE
            ->where('r.id_reparacion', $request->id_reparacion) 
            ->select(
                // 🔥 CORRECCIÓN: Usamos id_reparacion as folio
                'r.id_reparacion as folio',
                'r.taller_id', // Para que funcione la tiendita
                't.nombre_negocio as sucursal_asignada', 
                'e.marca',
                'e.modelo',
                'c.nombre as nombre_cliente',
                'r.estado as estado_reparacion',
                'r.falla_reportada',
                'r.diagnostico_tecnico',
                'r.presupuesto',
                DB::raw("DATE_FORMAT(r.created_at, '%Y-%m-%d') as fecha_ingreso")
            )
            ->first();

        // 3. Si alguien inventa un número, le decimos que no existe
        if (!$ticket) {
            return response()->json([
                'status' => false,
                'message' => 'El número de folio ingresado no existe. Por favor verifica tu ticket.'
            ], 404);
        }

        // 4. Si lo encuentra, lo devolvemos limpio
        return response()->json([
            'status' => true,
            'ticket' => $ticket
        ], 200);
    }
}