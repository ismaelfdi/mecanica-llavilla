<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura - Orden de Trabajo #{{ $ordenTrabajo->id }}</title>
    <style>
        body { font-family: sans-serif; margin: 0; padding: 20px; font-size: 11px; }
        .header, .footer { width: 100%; position: fixed; }
        .header { top: 0; }
        .footer { bottom: 0; text-align: center; font-size: 8px; border: 1px solid red;}
        .footertext { text-align: center; width: 100%; border: 1px solid green; }
        .logo { max-width: 150px; }
        .address { float: right; text-align: right; font-size: 12px; }
        .clearfix::after { content: ""; display: table; clear: both; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        .total-box { margin-top: 20px; float: right; width: 40%; }
        .total-box div { padding: 8px; border: 1px solid #ccc; }
        .total-box .total { font-weight: bold; }
    </style>
</head>
<body>
<div class="header clearfix">
    <img src="{{ public_path('images/logo.jpg') }}" alt="Logo Mecánica Llavilla" class="logo">
    <div class="address">
        <strong>Mecánica Llavilla</strong><br>
        Calle Antonio Vicent 68-70<br>
        Madrid 28019<br>
        645861484<br>
    </div>
</div>

<div style="margin-top: 120px;">
    <h2>ORDEN DE TRABAJO</h2>
    <p><strong>Nº Orden de Trabajo:</strong> #{{ $ordenTrabajo->id }}</p>
    <p><strong>Fecha de Emisión:</strong> {{ Carbon\Carbon::now()->format('d/m/Y') }}</p>
    <hr>
    <p><strong>Cliente:</strong> {{ $ordenTrabajo->vehiculo->cliente->nombre }}</p>
    <p><strong>Vehículo:</strong> {{ $ordenTrabajo->vehiculo->marca }} {{ $ordenTrabajo->vehiculo->modelo }} ({{ Str::upper($ordenTrabajo->vehiculo->matricula) }})</p>
    <p><strong>Diagnóstico:</strong> {{ $ordenTrabajo->diagnostico }}</p>
</div>

<table>
    <thead>
    <tr>
        <th>Concepto</th>
        <th>Cantidad</th>
        <th>Precio Unitario</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach($ordenTrabajo->items as $item)
        <tr>
            <td>{{ $item->descripcion }}</td>
            <td>{{ $item->pivot->cantidad }}</td>
            <td>{{ number_format($item->pivot->precio_aplicado, 2) }} €</td>
            <td>{{ number_format($item->pivot->linea_total, 2) }} €</td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="total-box">
    <div>
        <p><strong>Mano de Obra:</strong> {{ number_format($ordenTrabajo->total_mano_obra, 2) }} €</p>
        <p><strong>Repuestos:</strong> {{ number_format($ordenTrabajo->total_repuestos, 2) }} €</p>
    </div>
    <div class="total">
        <p><strong>TOTAL:</strong> {{ number_format($ordenTrabajo->total_general, 2) }} €</p>
    </div>
</div>
<div class="footer">
    <p class="footertext">Gracias por su confianza. | Mecánica Llavilla - Calle Antonio Vicent 68-70</p>
    <div style="text-align: center;">contenido a centrar</div>
</div>
</body>
</html>
