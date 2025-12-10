<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        .totales {
            font-weight: bold;
            background: #f0f0f0;
        }

        .resultado {
            font-size: 14px;
            font-weight: bold;
            text-align: right;
        }
    </style>
</head>

<body>
    <h2>ESTADO DE RESULTADOS</h2>

    @if ($fechaDesde && $fechaHasta)
        <p><strong>Desde:</strong> {{ $fechaDesde }} — <strong>Hasta:</strong> {{ $fechaHasta }}</p>
    @endif

    <br>

    <!-- INGRESOS -->
    <h3>INGRESOS</h3>
    <table width="100%" border="1" style="border-collapse: collapse">
        <tr style="font-weight:bold">
            <td>Código</td>
            <td>Nombre</td>
            <td style="text-align:right">Saldo (Bs)</td>
        </tr>
        @foreach ($resultados['ingresos'] as $item)
            <tr>
                <td>{{ $item['codigo_cuenta'] }}</td>
                <td>{{ $item['nombre'] }}</td>
                <td style="text-align:right">{{ number_format($item['saldo'], 2) }}</td>
            </tr>
        @endforeach
        <tr style="font-weight:bold;background:#eee">
            <td colspan="2">TOTAL INGRESOS</td>
            <td style="text-align:right">{{ number_format($resultados['total_ingresos'], 2) }}</td>
        </tr>
    </table>

    <br>

    <!-- EGRESOS -->
    <h3>EGRESOS</h3>
    <table width="100%" border="1" style="border-collapse: collapse">
        <tr style="font-weight:bold">
            <td>Código</td>
            <td>Nombre</td>
            <td style="text-align:right">Saldo (Bs)</td>
        </tr>
        @foreach ($resultados['egresos'] as $item)
            <tr>
                <td>{{ $item['codigo_cuenta'] }}</td>
                <td>{{ $item['nombre'] }}</td>
                <td style="text-align:right">{{ number_format($item['saldo'], 2) }}</td>
            </tr>
        @endforeach
        <tr style="font-weight:bold;background:#eee">
            <td colspan="2">TOTAL EGRESOS</td>
            <td style="text-align:right">{{ number_format($resultados['total_egresos'], 2) }}</td>
        </tr>
    </table>

    <br><br>

    <!-- RESULTADO NETO -->
    <h2 style="text-align:right">
        RESULTADO NETO:
        <span style="border-top:1px solid #000;">
            {{ number_format($resultados['resultado_neto'], 2) }} Bs
        </span>
    </h2>
</body>

</html>
