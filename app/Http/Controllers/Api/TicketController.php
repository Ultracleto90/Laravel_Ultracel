<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


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
                't.nombre_negocio', 
                'c.nombre as cliente', 'c.telefono as cliente_telefono',
                'e.marca', 'e.modelo', 'e.tipo_equipo', 'e.imei_o_serie',
                'r.id_reparacion as folio', 'r.pin_cliente', 'r.problema_reportado', 'r.presupuesto', 'r.fecha_recepcion'
            )
            ->first();

        if (!$ticketData) {
            return response()->json(['status' => false, 'message' => 'Reparación no encontrada'], 404);
        }

        // 🔥 LA MAGIA DEL QR: Fabricamos el código aquí mismo en Base64
        $urlRastreo = "https://www.mediafire.com/file/wnk9psfxp0ofo82/Ultracel.apk/file"; // Luego cambias esto por tu link corto de MediaFire
        $qrBase64 = base64_encode(QrCode::format('svg')->size(100)->margin(0)->generate($urlRastreo));

        // 2. Generamos el PDF inyectando el ticket Y el código QR ($qrBase64)
        $pdf = Pdf::loadView('tickets.recepcion', [
            'ticket' => $ticketData,
            'qrBase64' => $qrBase64 // <-- Le pasamos el QR a la vista HTML
        ])->setPaper([0, 0, 226.77, 550], 'portrait'); // 🔥 Aumentamos la altura de 500 a 550 para que quepa el cuadro negro

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

    public function generarTicketVenta(Request $request)
    {
        $request->validate(['taller_id' => 'required|integer', 'id_venta' => 'required|integer']);

        // 1. Obtener la cabecera de la venta
        $venta = DB::table('ventas as v')
            ->leftJoin('clientes as c', 'v.id_cliente', '=', 'c.id_cliente')
            ->join('users as u', 'v.id_vendedor', '=', 'u.id')
            ->join('talleres as t', 'v.taller_id', '=', 't.id')
            ->where('v.id_venta', $request->id_venta)
            ->where('v.taller_id', $request->taller_id)
            ->select(
                'v.id_venta as folio', 'v.fecha_venta', 'v.monto_total',
                'c.nombre as cliente_nombre', 'c.apellidos as cliente_apellidos',
                'u.name as vendedor', 't.nombre_negocio'
            )->first();

        if (!$venta) return response()->json(['status' => false, 'message' => 'Venta no encontrada'], 404);

        // 2. Obtener los productos/reparaciones cobradas
        $detalles = DB::table('venta_detalles')
            ->where('id_venta', $request->id_venta)
            ->select('cantidad', 'descripcion_linea as descripcion', 'precio_unitario')
            ->get();

        // 3. Generar PDF (El tamaño de papel es dinámico según los items para miniprinter térmica)
        $altura_dinamica = 300 + (count($detalles) * 20); 
        $pdf = Pdf::loadView('tickets.venta', ['venta' => $venta, 'detalles' => $detalles])
                  ->setPaper([0, 0, 226.77, $altura_dinamica], 'portrait');

        $fileName = 'venta_' . $venta->folio . '.pdf';
        $path = 'public/tickets/' . $fileName;
        Storage::put($path, $pdf->output());

        return response()->json(['status' => true, 'url_pdf' => asset('storage/tickets/' . $fileName)]);
    }
}