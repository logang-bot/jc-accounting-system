@extends('layouts.admin')

@section('content')
    <div class="bg-[var(--header-bg)] p-8">
        <div class="flex flex-wrap">
            <div class="w-full">
                <div class="flex justify-between items-center">
                    <h3 class="text-white text-2xl font-semibold">Estado de Resultados</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        {{-- Filters --}}
        <form method="GET" action="{{ route('estado-resultados.index') }}"
            class="flex flex-wrap items-end gap-4 bg-white p-4 rounded-lg shadow mb-6 w-full">

            <!-- Fecha Desde -->
            <div>
                <label for="fecha_desde" class="block text-sm font-medium text-gray-700">Desde</label>
                <input type="date" id="fecha_desde" name="fecha_desde" value="{{ $fechaDesde }}"
                    class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
            </div>

            <!-- Fecha Hasta -->
            <div>
                <label for="fecha_hasta" class="block text-sm font-medium text-gray-700">Hasta</label>
                <input type="date" id="fecha_hasta" name="fecha_hasta" value="{{ $fechaHasta }}"
                    class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
            </div>

            <!-- Botones -->
            <div class="flex gap-2">
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-[var(--header-bg)] text-white text-sm font-medium rounded-md shadow hover:bg-blue-700">
                    Filtrar
                </button>

                {{-- Limpiar filtros --}}
                <a href="{{ route('estado-resultados.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-500 text-white text-sm font-medium rounded-md shadow hover:bg-gray-600">
                    Limpiar
                </a>

                <a href="{{ route('estado_resultados.pdf', ['fecha_desde' => $fechaDesde, 'fecha_hasta' => $fechaHasta]) }}"
                    target="_blank"
                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md shadow hover:bg-red-700">
                    Reporte Estado de Resultados
                </a>
            </div>
        </form>


        @php
            // Función para renderizar cuenta con su jerarquía ascendente
            function renderCuentaConPadres($cuenta)
            {
                // Primero renderizar la cadena de padres
                if (!empty($cuenta['full_parent_chain'])) {
                    foreach ($cuenta['full_parent_chain'] as $i => $parent) {
                        echo '<tr class="border-b">';
                        echo '<td><span style="padding-left: ' .
                            $i * 20 .
                            'px;">' .
                            $parent['codigo_cuenta'] .
                            ' - ' .
                            $parent['nombre_cuenta'] .
                            '</span></td>';
                        echo '<td></td>';
                        echo '</tr>';
                    }
                }

                // Renderizar la cuenta final con saldo
                $level = count($cuenta['full_parent_chain'] ?? []);
                echo '<tr class="border-b">';
                echo '<td><span style="padding-left: ' .
                    $level * 20 .
                    'px;">' .
                    $cuenta['codigo_cuenta'] .
                    ' - ' .
                    $cuenta['nombre'] .
                    '</span></td>';
                echo '<td class="text-right">' . number_format($cuenta['saldo'], 2) . '</td>';
                echo '</tr>';
            }

            // Filtrar solo cuentas con saldo > 0
            $ingresosFiltrados = array_filter($resultados['ingresos'], fn($c) => $c['saldo'] != 0);
            $egresosFiltrados = array_filter($resultados['egresos'], fn($c) => $c['saldo'] != 0);
        @endphp

        @if (request()->filled('fecha_desde') && request()->filled('fecha_hasta'))
            {{-- Ingresos --}}
            <div class="mb-6">
                <h2 class="font-semibold text-lg mb-2">Ingresos</h2>
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-1">Cuenta</th>
                            <th class="text-right py-1">Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ingresosFiltrados as $cuenta)
                            @php renderCuentaConPadres($cuenta); @endphp
                        @endforeach
                        <tr class="font-semibold">
                            <td>Total Ingresos</td>
                            <td class="text-right">{{ number_format($resultados['total_ingresos'], 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Egresos --}}
            <div class="mb-6">
                <h2 class="font-semibold text-lg mb-2">Egresos</h2>
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-1">Cuenta</th>
                            <th class="text-right py-1">Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($egresosFiltrados as $cuenta)
                            @php renderCuentaConPadres($cuenta); @endphp
                        @endforeach
                        <tr class="font-semibold">
                            <td>Total Egresos</td>
                            <td class="text-right">{{ number_format($resultados['total_egresos'], 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Resultado Neto --}}
            <div class="text-right text-xl font-bold mt-4">
                Resultado Neto: {{ number_format($resultados['resultado_neto'], 2) }}
            </div>
        @else
            <div class="text-center text-gray-600 mt-6 text-lg">
                <b>Seleccione un rango de fechas y presione "Filtrar" para generar el reporte.</b>
            </div>
        @endif
    </div>
@endsection
