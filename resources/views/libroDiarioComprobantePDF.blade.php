@php
    $f = new NumberFormatter('spa', NumberFormatter::SPELLOUT);
    $text_debe_bs_debe = $f->format(number_format($comprobante->detalles->sum('debe'), 2));
    $text_debe_bs_haber = $f->format(number_format($comprobante->detalles->sum('haber'), 2));
    $text_debe_sus_debe = $f->format(number_format($comprobante->detalles->sum('debe') * $comprobante->tasa_cambio, 2));
    $text_debe_sus_haber = $f->format(
        number_format($comprobante->detalles->sum('haber') * $comprobante->tasa_cambio, 2),
    );
@endphp

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
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
            border-radius: 10px;
        }

        .empresa-info td,
        .comprobante-info td {
            padding: 3px 5px;
        }

        .detalles-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            border-radius: 10px;
        }

        .detalles-table th,
        .detalles-table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }

        .detalles-table .nombre_cuenta {
            text-align: left;
        }

        .totales {
            border: 1px solid #000;
            margin-top: 10px;
            padding: 1em;
            border-radius: 10px;
        }

        .signatures * {
            padding: 0px;
            margin: 0px;
        }

        .signatures {
            display: flex;
            border: 1px solid black;
            justify-content: space-around;
        }

        .signatures div {
            display: flex;
            flex-direction: column;
            justify-content: center;
            flex-grow: 1;
        }

        .signatures p {
            text-align: center;
            padding: 1em;
        }

        .signatures section {
            height: 7em;
        }
    </style>
</head>

<body>
    <section class="header">
        <div class="comprobante-location-info">
            <p>{{ $comprobante->empresa->nombre }}</p>
            <p>{{ $comprobante->empresa->provincia }}</p>
        </div>
        <div>
            <h2 class="comprobante-title">LIBRO DIARIO</h2>
            <h2 class="comprobante-title">(Expresado en Bolivianos y Dolares)</h2>
        </div>
        <div></div>
    </section>

    <table class="detalles-table">
        <thead>
            <tr>
                <th rowspan="2">Código</th>
                <th rowspan="2">Detalle</th>
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
                    <td class="nombre_cuenta">
                        <p>{{ $detalle->cuenta->nombre_cuenta }}</p>
                        <p>{{ $detalle->descripcion }}</p>
                    </td>
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

            <tr>
                <td colspan="2">TOTALES</td>
                <td>{{ number_format($comprobante->detalles->sum('debe'), 2) }}</td>
                <td>{{ number_format($comprobante->detalles->sum('haber'), 2) }}</td>
                <td>
                    {{ number_format($comprobante->detalles->sum('debe') * $comprobante->tasa_cambio, 2) }}
                </td>
                <td>
                    {{ number_format($comprobante->detalles->sum('haber') * $comprobante->tasa_cambio, 2) }}
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>
