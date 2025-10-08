<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Balance General</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #000;
        }

        h1,
        h2 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        .text-right {
            text-align: right;
        }

        .font-bold {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <h1>Balance General (Estado de Situación Financiera)</h1>
    <p style="text-align: center;">Desde: {{ $fechaDesde ?? '---' }} - Hasta: {{ $fechaHasta ?? '---' }}</p>

    {{-- Activos --}}
    <h2>Activos</h2>
    <table>
        <thead>
            <tr>
                <th>Cuenta</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($balances['activos'] ?? [] as $cuenta)
                @foreach ($cuenta['full_parent_chain'] ?? [] as $i => $parent)
                    <tr>
                        <td style="padding-left: {{ $i * 20 }}px;">{{ $parent }}</td>
                        <td></td>
                    </tr>
                @endforeach
                <tr>
                    <td style="padding-left: {{ ($cuenta['nivel'] ?? 0) * 20 }}px;">
                        {{ $cuenta['codigo'] ?? '' }} - {{ $cuenta['nombre'] ?? '' }}
                    </td>
                    <td class="text-right">{{ number_format($cuenta['saldo'] ?? 0, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="font-bold">
                <td>Total Activos</td>
                <td class="text-right">{{ number_format($balances['total_activos'] ?? 0, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    {{-- Pasivos y Patrimonio --}}
    <h2>Pasivos y Patrimonio</h2>
    <table>
        <thead>
            <tr>
                <th>Cuenta</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody>
            {{-- Pasivos --}}
            @foreach ($balances['pasivos'] ?? [] as $cuenta)
                @foreach ($cuenta['full_parent_chain'] ?? [] as $i => $parent)
                    <tr>
                        <td style="padding-left: {{ $i * 20 }}px;">{{ $parent }}</td>
                        <td></td>
                    </tr>
                @endforeach
                <tr>
                    <td style="padding-left: {{ ($cuenta['nivel'] ?? 0) * 20 }}px;">
                        {{ $cuenta['codigo'] ?? '' }} - {{ $cuenta['nombre'] ?? '' }}
                    </td>
                    <td class="text-right">{{ number_format($cuenta['saldo'] ?? 0, 2) }}</td>
                </tr>
            @endforeach

            {{-- Patrimonio --}}
            @foreach ($balances['patrimonio'] ?? [] as $cuenta)
                @foreach ($cuenta['full_parent_chain'] ?? [] as $i => $parent)
                    <tr>
                        <td style="padding-left: {{ $i * 20 }}px;">{{ $parent }}</td>
                        <td></td>
                    </tr>
                @endforeach
                <tr>
                    <td style="padding-left: {{ ($cuenta['nivel'] ?? 0) * 20 }}px;">
                        {{ $cuenta['codigo'] ?? '' }} - {{ $cuenta['nombre'] ?? '' }}
                    </td>
                    <td class="text-right">{{ number_format($cuenta['saldo'] ?? 0, 2) }}</td>
                </tr>
            @endforeach
        <tfoot>
            <tr class="font-bold">
                <td>Total Pasivos + Patrimonio</td>
                <td class="text-right">
                    {{ number_format(
                        ($balances['total_pasivos'] ?? 0) + ($balances['total_patrimonio'] ?? 0) + ($balances['resultado_neto'] ?? 0),
                        2,
                    ) }}
                </td>
            </tr>
        </tfoot>
    </table>
</body>

</html>
