<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orden de Trabajo #{{ $ordenTrabajo->id }}</title>
    <style>
        body { font-family: sans-serif; margin: 0; padding: 20px; font-size: 12px; }
        .header { width: 100%; margin-bottom: 40px; }
        .logo { max-width: 180px; float: left; }
        .invoice-details { float: right; text-align: right; }
        .clearfix::after { content: ""; display: table; clear: both; }

        .section-title { font-size: 16px; font-weight: bold; margin-bottom: 10px; }
        .details-box { width: 100%; display: inline-block; margin-top: 20px; }
        .to, .from { width: 48%; float: left; }
        .from { float: right; text-align: right; }

        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        /* thead { background-color: #e0f2fe; } */ /* Color de fondo azul claro */
        thead { background-color: #66CCCC; } /* Color de fondo verde claro */

        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }

        .total-box { margin-top: 20px; width: 40%; float: right; }
        .total-row { display: flex; justify-content: space-between; padding: 8px; border: 1px solid #ccc; }
        .total-row-grand { font-weight: bold; }
        .note { margin-top: 40px; padding-top: 10px; border-top: 1px solid #ccc; }
        .note p { font-size: 9px; color: #666; }

        .footer { width: 100%; position: fixed; bottom: 0; text-align: center; font-size: 8px; }
        .footertext { text-align: center; width: 100%; margin-bottom: 0; }

    </style>
</head>
<body>
<div class="header clearfix">
    <img src="{{ public_path('images/logo.jpg') }}" alt="Logo Mecánica Llavilla" class="logo">
    <div class="invoice-details">
        <div class="section-title">Orden de Trabajo #{{ $ordenTrabajo->id }}</div>
        <div>Fecha de Emisión: {{ Carbon\Carbon::now()->format('d/m/Y') }}</div>
    </div>
</div>

<div class="details-box clearfix">
    <div class="to">
        <div class="section-title">Cliente:</div>
        <div><strong>{{ $ordenTrabajo->vehiculo->cliente->nombre }}</strong></div>
        <div>{{ $ordenTrabajo->vehiculo->cliente->direccion }}</div>
        <div>{{ $ordenTrabajo->vehiculo->cliente->ciudad }}</div>
    </div>
    <div class="from">
        <div class="section-title">Taller:</div>
        <div><strong>Mecánica Llavilla</strong></div>
        <div>Calle Antonio Vicent 68-70</div>
        <div>Madrid 28019</div>
    </div>
</div>

<table>
    <thead>
    <tr>
        <th style="width: 50%;">Descripción</th>
        <th style="width: 15%; text-align: center;">Cantidad</th>
        <th style="width: 15%; text-align: right;">Precio</th>
        <th style="width: 20%; text-align: right;">Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach($ordenTrabajo->items as $item)
        <tr>
            <td>{{ $item->descripcion }}</td>
            <td style="text-align: center;">{{ $item->pivot->cantidad }}</td>
            <td style="text-align: right;">{{ number_format($item->pivot->precio_aplicado, 2) }} €</td>
            <td style="text-align: right;">{{ number_format($item->pivot->linea_total, 2) }} €</td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="total-box">
    <table>
        <tbody>
        <tr>
            <td style="border: none;">Subtotal Mano de Obra:</td>
            <td style="border: none; text-align: right;">{{ number_format($ordenTrabajo->total_mano_obra, 2) }} €</td>
        </tr>
        <tr>
            <td style="border: none;">Subtotal Repuestos:</td>
            <td style="border: none; text-align: right;">{{ number_format($ordenTrabajo->total_repuestos, 2) }} €</td>
        </tr>
        <tr>
            <td style="border: none; font-weight: bold;">TOTAL:</td>
            <td style="border: none; text-align: right; font-weight: bold;">{{ number_format($ordenTrabajo->total_general, 2) }} €</td>
        </tr>
        </tbody>
    </table>
</div>

<div class="note clearfix">
    <p>Este documento no es una factura fiscal.</p>
</div>


<div class="footer">
    <p class="footertext">Gracias por su confianza.</p>
    <p class="footertext">Mecánica Llavilla | Calle Antonio Vicent 68-70</p>
</div>
</body>
</html>
