<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Libro Mayor - {{ $cuenta->codigo_cuenta }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: right;
        }

        th {
            background: #f2f2f2;
        }

        td.text-left {
            text-align: left;
        }

        h2,
        h3 {
            margin: 0;
        }

        .header {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Libro Mayor</h2>
        <h3>{{ $cuenta->codigo_cuenta }} - {{ $cuenta->nombre_cuenta }}</h3>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-left">Fecha</th>
                <th class="text-left">Comprobante</th>
                <th class="text-left">Descripción</th>
                <th>Debe</th>
                <th>Haber</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody>
            @php $saldo = 0; @endphp
            @foreach ($libroMayor as $mov)
                @php
                    $saldo += $mov['debe'] - $mov['haber'];
                @endphp
                <tr>
                    <td class="text-left">{{ \Carbon\Carbon::parse($mov['fecha'])->format('d/m/Y') }}</td>
                    <td class="text-left">{{ $mov['comprobante'] }}</td>
                    <td class="text-left">{{ $mov['descripcion'] }}</td>
                    <td>{{ number_format($mov['debe'], 2) }}</td>
                    <td>{{ number_format($mov['haber'], 2) }}</td>
                    <td>{{ number_format($saldo, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
