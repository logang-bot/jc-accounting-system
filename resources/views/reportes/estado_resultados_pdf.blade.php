<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        .totales { font-weight: bold; background: #f0f0f0; }
        .resultado { font-size: 14px; font-weight: bold; text-align: right; }
    </style>
</head>
<body>
    <h2>Estado de Resultados</h2>
    <p><strong>Periodo:</strong> {{ $fechaInicio }} al {{ $fechaFin }}</p>

    <h3>Ingresos</h3>
    <table>
        <thead>
            <tr><th>Cuenta</th><th>Saldo</th></tr>
        </thead>
        <tbody>
            @foreach ($ingresos as $ingreso)
                <tr>
                    <td>{{ $ingreso['codigo'] }} - {{ $ingreso['nombre'] }}</td>
                    <td style="text-align: right">{{ number_format($ingreso['saldo'], 2) }}</td>
                </tr>
            @endforeach
            <tr class="totales">
                <td>Total Ingresos</td>
                <td style="text-align: right">{{ number_format($totalIngresos, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <h3>Egresos</h3>
    <table>
        <thead>
            <tr><th>Cuenta</th><th>Saldo</th></tr>
        </thead>
        <tbody>
            @foreach ($egresos as $egreso)
                <tr>
                    <td>{{ $egreso['codigo'] }} - {{ $egreso['nombre'] }}</td>
                    <td style="text-align: right">{{ number_format($egreso['saldo'], 2) }}</td>
                </tr>
            @endforeach
            <tr class="totales">
                <td>Total Egresos</td>
                <td style="text-align: right">{{ number_format($totalEgresos, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <p class="resultado">Resultado Neto: {{ number_format($resultadoNeto, 2) }}</p>
</body>
</html>

