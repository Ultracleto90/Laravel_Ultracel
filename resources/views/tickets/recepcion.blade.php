<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Courier New', Courier, monospace; font-size: 12px; margin: 0; padding: 10px; color: #000; }
        .text-center { text-align: center; }
        .bold { font-weight: bold; }
        .line { border-bottom: 1px dashed #000; margin: 10px 0; }
        h2, h3 { margin: 5px 0; }
        table { width: 100%; font-size: 11px; margin-top: 10px; }
        td { padding: 2px 0; }
        .qr-placeholder { border: 1px solid #000; width: 100px; height: 100px; margin: 10px auto; line-height: 100px; text-align: center; }
    </style>
</head>
<body>
    <div class="text-center">
        <h2>{{ $ticket->nombre_negocio }}</h2>
        <p>Tel: {{ $ticket->taller_telefono ?? 'Sin registrar' }}</p>
        <p>{{ date('d/m/Y h:i A', strtotime($ticket->fecha_recepcion)) }}</p>
    </div>

    <div class="line"></div>

    <div class="text-center">
        <h1 class="bold">FOLIO: {{ $ticket->folio }}</h1>
        <h3>PIN SECRETO: {{ $ticket->pin_cliente }}</h3>
    </div>

    <div class="line"></div>

    <table>
        <tr><td class="bold" style="width: 40%;">Cliente:</td><td>{{ $ticket->cliente }}</td></tr>
        <tr><td class="bold">Teléfono:</td><td>{{ $ticket->cliente_telefono }}</td></tr>
        <tr><td class="bold">Equipo:</td><td>{{ $ticket->marca }} {{ $ticket->modelo }}</td></tr>
        <tr><td class="bold">IMEI/Serie:</td><td>{{ $ticket->imei_o_serie ?? 'N/A' }}</td></tr>
    </table>

    <div class="line"></div>

    <p class="bold">Falla Reportada:</p>
    <p>{{ $ticket->problema_reportado }}</p>

    <div class="line"></div>
    
    <div class="text-center">
        <p class="bold">Presupuesto Aprox:</p>
        <h2>${{ number_format($ticket->presupuesto, 2) }}</h2>
    </div>

    <div class="text-center" style="margin-top: 20px;">
        <p>Rastrea tu equipo en:</p>
        <p class="bold">www.ultracel.lat/rastreo</p>
    </div>
</body>
</html>