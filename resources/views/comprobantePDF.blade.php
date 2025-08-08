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
            margin-bottom: 20px;
            display: flex;
            border: 1px solid black;
            border-radius: 10px;
            align-items: start;
            justify-content: space-between;
            flex: 1;
            gap: 1rem;
            padding: 5px;
        }

        .header .numero {
            border: 1px solid black;
            border-radius: 10px;
            padding: 1rem;
        }

        .header .comprobante-title {
            margin: 0 auto;
            flex: 1;
            text-align: center;
            align-self: center;
        }

        .comprobante-main-info .top-info {
            display: flex;
            flex-direction: row;
            gap: 5px;
        }

        .comprobante-main-info .top-info .fecha,
        .comprobante-main-info .top-info .tc {
            border: 1px solid black;
            border-radius: 5px;
            flex-grow: 1;
        }

        .top-info * {
            margin: 0;
        }

        .top-info .title {
            text-align: center;
        }

        .tc .value {
            padding: 5px;
        }

        .comprobante-main-info .top-info .fecha .fecha-detalles-container {
            display: flex;
            flex-direction: row;
            justify-content: space-evenly;
            align-items: normal;
        }

        .fecha-detalles-container p {
            padding: 5px;
        }

        .empresa-info,
        .comprobante-info {
            width: 100%;
            margin-bottom: 10px;
            border: 1px solid black;
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

    <section class="header">
        <div class="comprobante-location-info">
            <p>{{ $comprobante->empresa->nombre }}</p>
            <p>{{ $comprobante->empresa->provincia }}</p>
        </div>
        <h2 class="comprobante-title">COMPROBANTE DE {{ $comprobante->tipo }}</h2>
        <div class="comprobante-main-info">
            <div class="top-info">
                <div class="fecha">
                    <p class="title">Fecha</p>
                    <hr />
                    <div class="fecha-detalles-container">
                        <p>
                            {{ \Carbon\Carbon::parse($comprobante->fecha)->format('d') }}
                        </p>
                        <hr />
                        <p>
                            {{ \Carbon\Carbon::parse($comprobante->fecha)->format('m') }}
                        </p>
                        <hr />
                        <p>
                            {{ \Carbon\Carbon::parse($comprobante->fecha)->format('Y') }}
                        </p>
                    </div>
                </div>
                <div class="tc">
                    <p class="title">T.C.</p>
                    <hr />
                    <p class="value">
                        {{ number_format($comprobante->tasa_cambio, 2) }}
                    </p>
                </div>
            </div>
            <h3 class="numero">Nº {{ $comprobante->numero }}</h3>
        </div>
    </section>

    <table class="comprobante-info">
        <tr>
            <td><strong>LUGAR Y FECHA:</strong></td>
            <td>
                {{ \Carbon\Carbon::parse($comprobante->fecha)->format('d/m/Y') }}
            </td>
        </tr>
        <tr>
            <td><strong>PAGADO A:</strong></td>
            <td></td>
        </tr>
        <tr>
            <td><strong>CONCEPTO</strong></td>
            <td>{{ $comprobante->descripcion }}</td>
        </tr>
    </table>

    <h4>Detalle de cuentas:</h4>
    <table class="detalles-table">
        <thead>
            <tr>
                <th rowspan="2">Código</th>
                <th rowspan="2">Nombre cuenta</th>
                <th colspan="2">Bolivianos</th>
                <th colspan="2">Dolares</th>
            </tr>
            <tr>
                <th>Debe</th>
                <th>Haber</th>
                <th>Debe</th>
                <th>Haber</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($comprobante->detalles as $detalle)
                <tr>
                    <td>{{ $detalle->cuenta->codigo_cuenta }}</td>
                    <td>{{ $detalle->cuenta->nombre_cuenta }}</td>
                    <td style="text-align: right">
                        {{ number_format($detalle->debe, 2) }}
                    </td>
                    <td style="text-align: right">
                        {{ number_format($detalle->haber, 2) }}
                    </td>
                    <td style="text-align: right">
                        {{ number_format($detalle->debe, 2) }}
                    </td>
                    <td style="text-align: right">
                        {{ number_format($detalle->haber, 2) }}
                    </td>
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
