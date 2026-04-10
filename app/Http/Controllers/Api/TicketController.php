<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    public function generarTicketRecepcion(Request $request)
    {
        $request->validate([
            'taller_id' => 'required|integer',
            'id_reparacion' => 'required|integer'
        ]);

        // 1. Recopilamos todos los datos de la base de datos
        $ticketData = DB::table('reparaciones as r')
            ->join('equipos as e', 'r.id_equipo', '=', 'e.id_equipo')
            ->join('clientes as c', 'e.id_cliente', '=', 'c.id_cliente')
            ->join('talleres as t', 'r.taller_id', '=', 't.id')
            ->where('r.id_reparacion', $request->id_reparacion)
            ->where('r.taller_id', $request->taller_id)
            ->select(
                't.nombre_negocio', 't.telefono as taller_telefono',
                'c.nombre as cliente', 'c.telefono as cliente_telefono',
                'e.marca', 'e.modelo', 'e.tipo_equipo', 'e.imei_o_serie',
                'r.id_reparacion as folio', 'r.pin_cliente', 'r.problema_reportado', 'r.presupuesto', 'r.fecha_recepcion'
            )
            ->first();

        if (!$ticketData) {
            return response()->json(['status' => false, 'message' => 'Reparación no encontrada'], 404);
        }

        // 2. Generamos el PDF usando una vista de HTML (que crearemos en el siguiente paso)
        $pdf = Pdf::loadView('tickets.recepcion', ['ticket' => $ticketData])
                  ->setPaper([0, 0, 226.77, 500], 'portrait'); // Tamaño térmico 80mm

        // 3. Lo guardamos en el servidor (Ej: /storage/app/public/tickets/ticket_15.pdf)
        $fileName = 'ticket_' . $ticketData->folio . '.pdf';
        $path = 'public/tickets/' . $fileName;
        Storage::put($path, $pdf->output());

        // 4. Devolvemos la URL pública para que Python o React Native la puedan descargar
        $url = asset('storage/tickets/' . $fileName);

        return response()->json([
            'status' => true,
            'message' => 'Ticket generado con éxito',
            'url_pdf' => $url
        ]);
    }
}