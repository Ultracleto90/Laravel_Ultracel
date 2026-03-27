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
        // El cliente solo ingresa su folio, no sabe qué taller es
        $request->validate([
            'reparacion_id' => 'required|integer'
        ]);

        $detalles = DB::table('reparaciones as r')
            ->join('equipos as e', 'r.equipo_id', '=', 'e.id')
            ->join('clientes as c', 'e.cliente_id', '=', 'c.id')
            ->where('r.id_reparacion', $request->reparacion_id) // Corrección de la columna aplicada
            ->select(
                'r.id_reparacion as id', // Corrección aplicada
                'r.taller_id', // ¡Le devolvemos el taller_id a tu compañero para que su app fluya!
                'e.marca',
                'e.modelo',
                'c.nombre as cliente_nombre',
                'r.estado',
                'r.falla_reportada',
                'r.diagnostico_tecnico',
                'r.presupuesto'
                // Omitimos la contraseña de desbloqueo aquí por ser un portal público
            )
            ->first();

        if (!$detalles) {
            return response()->json(['status' => false, 'message' => 'Folio no encontrado. Verifica tu ticket.'], 404);
        }

        return response()->json(['status' => true, 'detalles' => $detalles]);
    }
    // Función para el Ticket: consultarTicket
    public function consultarTicket(Request $request)
    {
        // 1. Validamos que Eduardo nos mande el folio en el JSON
        $request->validate([
            'id_reparacion' => 'required|integer' // Este es el nombre del campo que Eduardo enviará
        ]);

        // 2. Buscamos el folio en toda la base de datos (SIN filtro de taller_id)
        $ticket = \Illuminate\Support\Facades\DB::table('reparaciones as r')
            ->join('equipos as e', 'r.equipo_id', '=', 'e.id')
            ->join('clientes as c', 'e.cliente_id', '=', 'c.id')
            ->join('talleres as t', 'r.taller_id', '=', 't.id') // Unimos con talleres para decirle en qué sucursal está
            ->where('r.id', $request->id_reparacion) // Aquí usamos r.id porque así se llama tu columna en la DB
            ->select(
                'r.id as folio',
                't.nombre_negocio as sucursal_asignada', // Le decimos en qué taller está su equipo
                'e.marca',
                'e.modelo',
                'c.nombre as nombre_cliente',
                'r.estado as estado_reparacion',
                'r.falla_reportada',
                'r.diagnostico_tecnico',
                'r.presupuesto',
                \Illuminate\Support\Facades\DB::raw("DATE_FORMAT(r.created_at, '%Y-%m-%d') as fecha_ingreso")
            )
            ->first();

        // 3. Si alguien inventa un número, le decimos que no existe
        if (!$ticket) {
            return response()->json([
                'status' => false,
                'message' => 'El número de folio ingresado no existe. Por favor verifica tu ticket.'
            ], 404);
        }

        // 4. Si lo encuentra, le devolvemos los datos limpios a Eduardo
        return response()->json([
            'status' => true,
            'ticket' => $ticket
        ], 200);
    }
}