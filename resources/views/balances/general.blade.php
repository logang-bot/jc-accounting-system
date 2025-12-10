@extends('layouts.admin')

@section('content')
    <div class="bg-[var(--header-bg)] p-8">
        <div class="flex flex-wrap">
            <div class="w-full">
                <div class="flex justify-between items-center">
                    <h3 class="text-white text-2xl font-semibold">Balance General (Estado de Situación Financiera)</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-6">
        {{-- Filters --}}
        <form method="GET" action="{{ route('balances.general') }}"
            class="flex flex-wrap items-end gap-4 bg-white p-4 rounded-lg shadow mb-6 w-full">

            <div>
                <label for="fecha_desde" class="block text-sm font-medium text-gray-700">Desde</label>
                <input type="date" id="fecha_desde" name="fecha_desde" value="{{ $fechaDesde }}"
                    class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
            </div>

            <div>
                <label for="fecha_hasta" class="block text-sm font-medium text-gray-700">Hasta</label>
                <input type="date" id="fecha_hasta" name="fecha_hasta" value="{{ $fechaHasta }}"
                    class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
            </div>

            <div class="flex gap-2">
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-[var(--header-bg)] text-white text-sm font-medium rounded-md shadow hover:bg-blue-700">
                    Filtrar
                </button>

                <a href="{{ route('balances.pdf', ['fecha_desde' => $fechaDesde, 'fecha_hasta' => $fechaHasta]) }}"
                    target="_blank"
                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md shadow hover:bg-red-700">
                    Reporte Balance General
                </a>
            </div>
        </form>

        {{-- Balance Table --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            {{-- Activos --}}
            <div class="bg-white shadow rounded p-4">
                <h2 class="text-xl font-semibold mb-4">Activos</h2>
                <table class="w-full text-sm">
                    <tbody>
                        @foreach ($balances['activos'] ?? [] as $cuenta)
                            @foreach ($cuenta['full_parent_chain'] ?? [] as $i => $parent)
                                <tr>
                                    <td>
                                        <span class="inline-block" style="padding-left: {{ $i * 20 }}px;">
                                            {{ $parent['codigo'] ?? '' }} - {{ $parent['nombre'] ?? '' }}
                                        </span>
                                    </td>
                                    <td></td>
                                </tr>
                            @endforeach
                            <tr>
                                <td>
                                    <span class="inline-block" style="padding-left: {{ ($cuenta['nivel'] ?? 0) * 20 }}px;">
                                        {{ $cuenta['codigo_cuenta'] ?? '' }} - {{ $cuenta['nombre'] ?? '' }}
                                    </span>
                                </td>
                                <td class="text-right">{{ number_format($cuenta['saldo'] ?? 0, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="font-bold border-t">
                            <td>Total Activos</td>
                            <td class="text-right">{{ number_format($balances['total_activos'] ?? 0, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>


            {{-- Pasivos + Patrimonio --}}
            <div class="bg-white shadow rounded p-4">
                <h2 class="text-xl font-semibold mb-4">Pasivos y Patrimonio</h2>
                <table class="w-full text-sm">
                    <tbody>
                        {{-- 🔹 PASIVOS --}}
                        @foreach ($balances['pasivos'] ?? [] as $cuenta)
                            @foreach ($cuenta['full_parent_chain'] ?? [] as $i => $parent)
                                <tr>
                                    <td>
                                        <span class="inline-block" style="padding-left: {{ $i * 20 }}px;">
                                            {{ $parent['codigo'] ?? '' }} - {{ $parent['nombre'] ?? '' }}
                                        </span>
                                    </td>
                                    <td></td>
                                </tr>
                            @endforeach
                            <tr>
                                <td>
                                    <span class="inline-block" style="padding-left: {{ ($cuenta['nivel'] ?? 0) * 20 }}px;">
                                        {{ $cuenta['codigo_cuenta'] ?? '' }} - {{ $cuenta['nombre'] ?? '' }}
                                    </span>
                                </td>
                                <td class="text-right">{{ number_format($cuenta['saldo'] ?? 0, 2) }}</td>
                            </tr>
                        @endforeach

                        {{-- 🔹 PATRIMONIO --}}
                        @foreach ($balances['patrimonio'] ?? [] as $cuenta)
                            @if (($cuenta['saldo'] ?? 0) != 0)
                                {{-- mostrar solo si tiene saldo --}}
                                @foreach ($cuenta['full_parent_chain'] ?? [] as $i => $parent)
                                    <tr>
                                        <td>
                                            <span class="inline-block" style="padding-left: {{ $i * 20 }}px;">
                                                {{ $parent['codigo'] ?? '' }} - {{ $parent['nombre'] ?? '' }}
                                            </span>
                                        </td>
                                        <td></td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td>
                                        <span class="inline-block"
                                            style="padding-left: {{ ($cuenta['nivel'] ?? 0) * 20 }}px;">
                                            {{ $cuenta['codigo_cuenta'] ?? '' }} - {{ $cuenta['nombre'] ?? '' }}
                                        </span>
                                    </td>
                                    <td class="text-right">{{ number_format($cuenta['saldo'] ?? 0, 2) }}</td>
                                </tr>
                            @endif
                        @endforeach

                        {{-- 🔹 RESULTADO DE EJERCICIOS --}}
                        @if (isset($balances['resultado']) && count($balances['resultado']) > 0)
                            @foreach ($balances['resultado'] as $cuenta)
                                @if (($cuenta['saldo'] ?? 0) != 0)
                                    {{-- mostrar solo si tiene saldo --}}
                                    @foreach ($cuenta['full_parent_chain'] ?? [] as $i => $parent)
                                        <tr>
                                            <td>
                                                <span class="inline-block" style="padding-left: {{ $i * 20 }}px;">
                                                    {{ $parent['codigo'] ?? '' }} - {{ $parent['nombre'] ?? '' }}
                                                </span>
                                            </td>
                                            <td></td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td>
                                            <span class="inline-block"
                                                style="padding-left: {{ ($cuenta['nivel'] ?? 0) * 20 }}px;">
                                                {{ $cuenta['codigo_cuenta'] ?? '' }} - {{ $cuenta['nombre'] ?? '' }}
                                            </span>
                                        </td>
                                        <td class="text-right">{{ number_format($cuenta['saldo'] ?? 0, 2) }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        @elseif(isset($balances['resultado_neto']) && $balances['resultado_neto'] != 0)
                            <tr>
                                <td>
                                    <span class="inline-block" style="padding-left: 60px;">
                                        3301010000 - Resultado de Ejercicios
                                    </span>
                                </td>
                                <td class="text-right">
                                    {{ number_format($balances['resultado_neto'], 2) }}
                                </td>
                            </tr>
                        @endif

                    </tbody>

                    <tfoot>
                        <tr class="font-bold border-t">
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
            </div>

        </div>

    </div>
@endsection
