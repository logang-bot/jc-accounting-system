@php
    // Totales del comprobante
    $total_debe_bs = $comprobante->detalles->sum('debe');
    $total_haber_bs = $comprobante->detalles->sum('haber');
    $total_debe_usd = $total_debe_bs / $comprobante->tasa_cambio;
    $total_haber_usd = $total_haber_bs / $comprobante->tasa_cambio;

    // Función para convertir número a literal estilo bancario
    function convertirALetras($numero)
    {
        $f = new \NumberFormatter('es', \NumberFormatter::SPELLOUT);

        $entero = floor($numero);
        $decimales = round(($numero - $entero) * 100);

        $texto_entero = strtoupper($f->format($entero));
        $texto_decimales = str_pad($decimales, 2, '0', STR_PAD_LEFT);

        return "$texto_entero, $texto_decimales/100";
    }

    // Expresiones literales solo tomando el total Debe
    $texto_total_bs = convertirALetras($total_debe_bs) . ' Bolivianos';
    $texto_total_usd = convertirALetras($total_debe_usd) . ' Dólares';
@endphp

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
            text-align: center
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
            <p>{{ $comprobante->empresa->name }}</p>
            <p>{{ $comprobante->empresa->provincia }}</p>
        </div>
        <h2 class="comprobante-title">COMPROBANTE DE {{ $comprobante->tipo }}</h2>
        <div class="comprobante-main-info">
            <div class="top-info">
                <div class="fecha">
                    <p class="title">Fecha</p>
                    <hr />
                    <div class="fecha-detalles-container">
                        <p>{{ \Carbon\Carbon::parse($comprobante->fecha)->format('d') }}</p>
                        <hr />
                        <p>{{ \Carbon\Carbon::parse($comprobante->fecha)->format('m') }}</p>
                        <hr />
                        <p>{{ \Carbon\Carbon::parse($comprobante->fecha)->format('Y') }}</p>
                    </div>
                </div>
                <div class="tc">
                    <p class="title">T.C.</p>
                    <hr />
                    <p class="value">{{ number_format($comprobante->tasa_cambio, 2) }}</p>
                </div>
            </div>
            <h3 class="numero">Nº {{ $comprobante->numero }}</h3>
        </div>
    </section>

    <table class="comprobante-info">
        <tr>
            <td><strong>FECHA:</strong></td>
            <td>{{ \Carbon\Carbon::parse($comprobante->fecha)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td><strong>PAGADO A:</strong></td>
            <td>{{ $comprobante->destinatario }}</td>
        </tr>
        <tr>
            <td><strong>CONCEPTO:</strong></td>
            <td>{{ $comprobante->descripcion }}</td>
        </tr>
    </table>

    <h4>Detalle de cuentas:</h4>
    <table class="detalles-table">
        <thead>
            <tr>
                <th rowspan="2">Código</th>
                <th rowspan="2">Nombre cuenta y Referencia</th>
                <th colspan="2">Bolivianos</th>
                <th colspan="2">Dólares</th>
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
                        <p>REF. --> {{ $detalle->descripcion }}</p>
                    </td>
                    <td style="text-align: right">{{ number_format($detalle->debe, 2) }}</td>
                    <td style="text-align: right">{{ number_format($detalle->haber, 2) }}</td>
                    <td style="text-align: right">{{ number_format($detalle->debe / $comprobante->tasa_cambio, 2) }}
                    </td>
                    <td style="text-align: right">{{ number_format($detalle->haber / $comprobante->tasa_cambio, 2) }}
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2">TOTALES</td>
                <td>{{ number_format($total_debe_bs, 2) }}</td>
                <td>{{ number_format($total_haber_bs, 2) }}</td>
                <td>{{ number_format($total_debe_usd, 2) }}</td>
                <td>{{ number_format($total_haber_usd, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="totales">
        <p><strong>Son:</strong> {{ $texto_total_bs }}</p>
        <p><strong>Son:</strong> {{ $texto_total_usd }}</p>
    </div>

    <br />
    <div class="signatures">
        <div>
            <section></section>
            <hr />
            <p>Preparado por</p>
        </div>
        <hr />
        <div>
            <section></section>
            <hr />
            <p>Revisado por</p>
        </div>
        <hr />
        <div>
            <section></section>
            <hr />
            <p>Recibi conforme</p>
        </div>
    </div>

</body>

</html>
