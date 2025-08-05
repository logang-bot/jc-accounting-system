<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Comprobante Nº {{ $comprobante->numero }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #000;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .empresa-info,
        .comprobante-info {
            width: 100%;
            margin-bottom: 10px;
        }

        .empresa-info td,
        .comprobante-info td {
            padding: 3px 5px;
        }

        .detalles-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .detalles-table th,
        .detalles-table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        .totales {
            margin-top: 10px;
            text-align: right;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>{{ $comprobante->empresa->nombre }}</h2>
        <p>{{ $comprobante->empresa->direccion }}</p>
        <p>{{ $comprobante->empresa->ciudad }}, {{ $comprobante->empresa->provincia }}</p>
        <p><strong>NIT:</strong> {{ $comprobante->empresa->nit }}</p>
        <hr>
        <h3>Comprobante Nº {{ $comprobante->numero }}</h3>
    </div>

    <table class="comprobante-info">
        <tr>
            <td><strong>Fecha:</strong></td>
            <td>{{ \Carbon\Carbon::parse($comprobante->fecha)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td><strong>Tipo:</strong></td>
            <td>{{ $comprobante->tipo }}</td>
        </tr>
        <tr>
            <td><strong>Descripción:</strong></td>
            <td>{{ $comprobante->descripcion }}</td>
        </tr>
        <tr>
            <td><strong>Tasa de cambio:</strong></td>
            <td>{{ number_format($comprobante->tasa_cambio, 2) }}</td>
        </tr>
    </table>

    <h4>Detalle de cuentas:</h4>
    <table class="detalles-table">
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre cuenta</th>
                <th>Descripción</th>
                <th>Debe</th>
                <th>Haber</th>
                <th>IVA</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($comprobante->detalles as $detalle)
                <tr>
                    <td>{{ $detalle->cuenta->codigo_cuenta }}</td>
                    <td>{{ $detalle->cuenta->nombre_cuenta }}</td>
                    <td>{{ $detalle->descripcion }}</td>
                    <td style="text-align: right;">{{ number_format($detalle->debe, 2) }}</td>
                    <td style="text-align: right;">{{ number_format($detalle->haber, 2) }}</td>
                    <td style="text-align: center;">{{ $detalle->iva ? 'Sí' : 'No' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totales">
        <p><strong>Total Debe:</strong> {{ number_format($comprobante->detalles->sum('debe'), 2) }}</p>
        <p><strong>Total Haber:</strong> {{ number_format($comprobante->detalles->sum('haber'), 2) }}</p>
    </div>

    <br><br><br>
    <table style="width: 100%; margin-top: 40px;">
        <tr>
            <td style="text-align: center;">_______________________<br>Elaborado por</td>
            <td style="text-align: center;">_______________________<br>Revisado por</td>
            <td style="text-align: center;">_______________________<br>Aprobado por</td>
        </tr>
    </table>

</body>

</html>
