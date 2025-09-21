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
                <th class="px-4 py-2 text-left font-medium text-gray-600">Fecha</th>
                <th class="px-4 py-2 text-left font-medium text-gray-600">Comprobante</th>
                <th class="px-4 py-2 text-left font-medium text-gray-600">Descripción</th>

                @if (request('moneda') == 'bs' || request('moneda') == 'ambas')
                    <th class="px-4 py-2 text-right font-medium text-gray-600">Debe (Bs)</th>
                    <th class="px-4 py-2 text-right font-medium text-gray-600">Haber (Bs)</th>
                    <th class="px-4 py-2 text-right font-medium text-gray-600">Saldo (Bs)</th>
                @endif

                @if (request('moneda') == 'usd' || request('moneda') == 'ambas')
                    <th class="px-4 py-2 text-right font-medium text-gray-600">Debe (USD)</th>
                    <th class="px-4 py-2 text-right font-medium text-gray-600">Haber (USD)</th>
                    <th class="px-4 py-2 text-right font-medium text-gray-600">Saldo (USD)</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @php
                $saldoBs = 0;
                $saldoUsd = 0;
                $totalDebeBs = 0;
                $totalHaberBs = 0;
                $totalDebeUsd = 0;
                $totalHaberUsd = 0;
            @endphp
            @foreach ($libroMayor as $mov)
                @php
                    $debeBs = $mov['debe'] ?? 0;
                    $haberBs = $mov['haber'] ?? 0;
                    $debeUsd = $mov['debe'] / $mov['comprobante']->tasa_cambio;
                    $haberUsd = $mov['haber'] / $mov['comprobante']->tasa_cambio;

                    $saldoBs += $debeBs - $haberBs;
                    $saldoUsd += $debeUsd - $haberUsd;

                    $totalDebeBs += $debeBs;
                    $totalHaberBs += $haberBs;
                    $totalDebeUsd += $debeUsd;
                    $totalHaberUsd += $haberUsd;
                @endphp
                <tr>
                    <td class="text-left">{{ \Carbon\Carbon::parse($mov['fecha'])->format('d/m/Y') }}</td>
                    <td class="text-left">{{ $mov['comprobante']['numero'] }}</td>
                    <td class="text-left">{{ $mov['descripcion'] }}</td>

                    @if (request('moneda') == 'bs' || request('moneda') == 'ambas')
                        <td class="px-4 py-2 text-right border border-gray-300 rounded">
                            {{ number_format($debeBs, 2) }}</td>
                        <td class="px-4 py-2 text-right border border-gray-300 rounded">
                            {{ number_format($haberBs, 2) }}</td>
                        <td class="px-4 py-2 text-right font-medium text-gray-900 border border-gray-300 rounded">
                            {{ number_format($saldoBs, 2) }}</td>
                    @endif

                    @if (request('moneda') == 'usd' || request('moneda') == 'ambas')
                        <td class="px-4 py-2 text-right border border-gray-300 rounded">
                            {{ number_format($debeUsd, 2) }}</td>
                        <td class="px-4 py-2 text-right border border-gray-300 rounded">
                            {{ number_format($haberUsd, 2) }}</td>
                        <td class="px-4 py-2 text-right font-medium text-gray-900 border border-gray-300 rounded">
                            {{ number_format($saldoUsd, 2) }}</td>
                    @endif
                    {{-- <td>{{ number_format($mov['debe'], 2) }}</td>
                    <td>{{ number_format($mov['haber'], 2) }}</td>
                    <td>{{ number_format($saldo, 2) }}</td> --}}
                </tr>
            @endforeach
        </tbody>
        <tfoot class="bg-gray-50">
            <tr>
                <th colspan="3"
                    class="px-4 py-2 text-right font-semibold text-gray-700 border border-gray-300 rounded">
                    Totales</th>

                @if (request('moneda') == 'bs' || request('moneda') == 'ambas')
                    <th class="px-4 py-2 text-right font-semibold text-gray-700 border border-gray-300 rounded">
                        {{ number_format($totalDebeBs, 2) }}</th>
                    <th class="px-4 py-2 text-right font-semibold text-gray-700 border border-gray-300 rounded">
                        {{ number_format($totalHaberBs, 2) }}</th>
                    <th class="px-4 py-2 text-right font-bold text-gray-900 border border-gray-300 rounded">
                        {{ number_format($saldoBs, 2) }}</th>
                @endif

                @if (request('moneda') == 'usd' || request('moneda') == 'ambas')
                    <th class="px-4 py-2 text-right font-semibold text-gray-700 border border-gray-300 rounded">
                        {{ number_format($totalDebeUsd, 2) }}</th>
                    <th class="px-4 py-2 text-right font-semibold text-gray-700 border border-gray-300 rounded">
                        {{ number_format($totalHaberUsd, 2) }}</th>
                    <th class="px-4 py-2 text-right font-bold text-gray-900 border border-gray-300 rounded">
                        {{ number_format($saldoUsd, 2) }}</th>
                @endif
            </tr>
        </tfoot>
    </table>
</body>

</html>
