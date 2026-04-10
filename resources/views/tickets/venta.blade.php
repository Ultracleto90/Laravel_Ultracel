<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Courier New', Courier, monospace; font-size: 11px; margin: 0; padding: 10px; color: #000; }
        .text-center { text-align: center; }
        .bold { font-weight: bold; }
        .line { border-bottom: 1px dashed #000; margin: 8px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        th { border-bottom: 1px solid #000; text-align: left; padding-bottom: 3px; }
        td { padding: 3px 0; vertical-align: top; }
        .right { text-align: right; }
    </style>
</head>
<body>
    <div class="text-center">
        <h2 style="margin:0;">{{ $venta->nombre_negocio }}</h2>
        <p style="margin:2px 0;">Tel: {{ $venta->taller_telefono ?? 'Sin registrar' }}</p>
        <p style="margin:2px 0;">{{ date('d/m/Y h:i A', strtotime($venta->fecha_venta)) }}</p>
    </div>
    <div class="line"></div>
    <div>
        <p style="margin:2px 0;"><span class="bold">TICKET VENTA:</span> #{{ $venta->folio }}</p>
        <p style="margin:2px 0;"><span class="bold">Atendió:</span> {{ $venta->vendedor }}</p>
        <p style="margin:2px 0;"><span class="bold">Cliente:</span> {{ $venta->cliente_nombre ? $venta->cliente_nombre.' '.$venta->cliente_apellidos : 'Venta de Mostrador' }}</p>
    </div>
    <div class="line"></div>
    <table>
        <thead>
            <tr>
                <th style="width: 15%;">Cant</th>
                <th style="width: 55%;">Descripción</th>
                <th style="width: 30%;" class="right">Importe</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detalles as $d)
            <tr>
                <td class="text-center">{{ $d->cantidad }}</td>
                <td>{{ $d->descripcion }}</td>
                <td class="right">${{ number_format($d->cantidad * $d->precio_unitario, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="line"></div>
    <div class="right">
        <h2 style="margin: 5px 0;">TOTAL: ${{ number_format($venta->monto_total, 2) }}</h2>
    </div>
    <div class="text-center" style="margin-top: 20px;">
        <p class="bold">¡Gracias por tu preferencia!</p>
    </div>
</body>
</html>