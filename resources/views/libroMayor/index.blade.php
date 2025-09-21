@extends('layouts.admin')

@section('content')
    <div class="bg-blue-600 p-8">
        <div class="flex flex-wrap">
            <div class="w-full">
                <div class="flex justify-between items-center">
                    <h3 class="text-white text-2xl font-semibold">Libro Mayor</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        {{-- Filtros --}}
        <form method="GET" action="{{ route('show.libro-mayor.index') }}"
            class="mb-6 grid grid-cols-1 md:grid-cols-6 gap-4 bg-white p-4 rounded-xl shadow text-sm">

            {{-- Código Contable --}}
            <div class="col-span-2">
                <label for="cuenta" class="block font-medium text-gray-700">Código Contable</label>
                <select id="cuenta" name="cuenta"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Todas</option>
                    @foreach ($cuentas as $cu)
                        <option value="{{ $cu->id_cuenta }}" @selected(request('cuenta') == $cu->id_cuenta)>
                            {{ $cu->codigo_cuenta }} - {{ $cu->nombre_cuenta }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Rango de Fechas --}}
            <div class="col-span-2 flex gap-2">
                <div class="w-1/2">
                    <label for="fecha_desde" class="block font-medium text-gray-700">Desde</label>
                    <input type="date" name="fecha_desde" id="fecha_desde" value="{{ request('fecha_desde') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="w-1/2">
                    <label for="fecha_hasta" class="block font-medium text-gray-700">Hasta</label>
                    <input type="date" name="fecha_hasta" id="fecha_hasta" value="{{ request('fecha_hasta') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            {{-- Moneda --}}
            <div>
                <label for="moneda" class="block font-medium text-gray-700">Moneda</label>
                <select name="moneda" id="moneda"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="bs" @selected(request('moneda') == 'bs')>Bs.</option>
                    <option value="usd" @selected(request('moneda') == 'usd')>US$</option>
                    <option value="ambas" @selected(request('moneda') == 'ambas')>Ambas</option>
                </select>
            </div>

            {{-- Tipo de Saldo --}}
            <div>
                <label for="saldo" class="block font-medium text-gray-700">Tipo de Saldo</label>
                <select name="saldo" id="saldo"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="con_saldo" @selected(request('saldo') == 'con_saldo')>Con Saldo</option>
                    <option value="sin_saldo" @selected(request('saldo') == 'sin_saldo')>Sin Saldo</option>
                </select>
            </div>

            {{-- Botones --}}
            <div class="col-span-6 flex justify-end items-end gap-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Procesar</button>
                <a href="{{ route('show.libro-mayor.index') }}"
                    class="px-4 py-2 bg-gray-100 text-gray-800 rounded hover:bg-gray-200">Cancelar</a>

                @if ($cuentaSeleccionada)
                    <a href="{{ route('libro-mayor.pdf', [
                        'cuenta' => $cuentaSeleccionada,
                        'moneda' => request('moneda'),
                        'fecha_desde' => request('fecha_desde'),
                        'fecha_hasta' => request('fecha_hasta'),
                    ]) }}"
                        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                        Visualizar PDF
                    </a>
                @endif
            </div>
        </form>

        {{-- Resultados --}}
        @forelse ($libroMayor as $cuentaId => $cuentaData)
            <div class="bg-white rounded-xl shadow mb-8 overflow-x-auto">
                <div class="px-6 py-3 bg-gray-50 border-b">
                    <h2 class="text-lg font-semibold text-gray-800">
                        {{ $cuentaData['codigo'] }} — {{ $cuentaData['nombre'] }}
                    </h2>
                </div>

                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-100">
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
                    <tbody class="divide-y divide-gray-100">
                        @php
                            $saldoBs = 0;
                            $saldoUsd = 0;
                            $totalDebeBs = 0;
                            $totalHaberBs = 0;
                            $totalDebeUsd = 0;
                            $totalHaberUsd = 0;
                        @endphp

                        @foreach ($cuentaData['movimientos'] as $mov)
                            @php
                                $debeBs = $mov->debe ?? 0;
                                $haberBs = $mov->haber ?? 0;
                                $debeUsd = $mov->debe_usd ?? $mov->debe / $mov->comprobante->tasa_cambio;
                                $haberUsd = $mov->haber_usd ?? $mov->haber / $mov->comprobante->tasa_cambio;

                                $saldoBs += $debeBs - $haberBs;
                                $saldoUsd += $debeUsd - $haberUsd;

                                $totalDebeBs += $debeBs;
                                $totalHaberBs += $haberBs;
                                $totalDebeUsd += $debeUsd;
                                $totalHaberUsd += $haberUsd;
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2 text-gray-700 border border-gray-300 rounded">
                                    {{ optional($mov->comprobante)->fecha ? \Carbon\Carbon::parse($mov->comprobante->fecha)->format('d/m/Y') : '—' }}
                                </td>
                                <td class="px-4 py-2 text-gray-700 border border-gray-300 rounded">
                                    {{ $mov->comprobante->numero ?? '—' }}</td>
                                <td class="px-4 py-2 text-gray-700 border border-gray-300 rounded">
                                    {{ $mov->descripcion ?? '—' }}</td>

                                @if (request('moneda') == 'bs' || request('moneda') == 'ambas')
                                    <td class="px-4 py-2 text-right border border-gray-300 rounded">
                                        {{ number_format($debeBs, 2) }}</td>
                                    <td class="px-4 py-2 text-right border border-gray-300 rounded">
                                        {{ number_format($haberBs, 2) }}</td>
                                    <td
                                        class="px-4 py-2 text-right font-medium text-gray-900 border border-gray-300 rounded">
                                        {{ number_format($saldoBs, 2) }}</td>
                                @endif

                                @if (request('moneda') == 'usd' || request('moneda') == 'ambas')
                                    <td class="px-4 py-2 text-right border border-gray-300 rounded">
                                        {{ number_format($debeUsd, 2) }}</td>
                                    <td class="px-4 py-2 text-right border border-gray-300 rounded">
                                        {{ number_format($haberUsd, 2) }}</td>
                                    <td
                                        class="px-4 py-2 text-right font-medium text-gray-900 border border-gray-300 rounded">
                                        {{ number_format($saldoUsd, 2) }}</td>
                                @endif
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
            </div>
        @empty
            <div class="text-center text-gray-500 py-10">
                <p>No se encontraron movimientos para el criterio seleccionado.</p>
            </div>
        @endforelse
    </div>
@endsection
