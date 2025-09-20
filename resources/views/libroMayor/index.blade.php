@extends('layouts.admin')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
            📘 Libro Mayor
        </h1>

        {{-- Filters --}}
        <form method="GET" action="{{ route('libro-mayor.index') }}"
            class="mb-6 grid grid-cols-1 sm:grid-cols-3 gap-4 bg-white p-4 rounded-xl shadow">
            <div>
                <label for="fecha_desde" class="block text-sm font-medium text-gray-700">Desde</label>
                <input type="date" name="fecha_desde" id="fecha_desde" value="{{ request('fecha_desde') }}"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
            </div>

            <div>
                <label for="fecha_hasta" class="block text-sm font-medium text-gray-700">Hasta</label>
                <input type="date" name="fecha_hasta" id="fecha_hasta" value="{{ request('fecha_hasta') }}"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
            </div>

            <div class="flex items-end">
                <button type="submit"
                    class="w-full inline-flex justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                    Filtrar
                </button>
            </div>
        </form>

        {{-- Results --}}
        @foreach ($libroMayor as $cuentaId => $cuentaData)
            <div class="bg-white rounded-xl shadow mb-8 overflow-hidden">
                <div class="px-6 py-3 bg-gray-50 border-b">
                    <h2 class="text-lg font-semibold text-gray-800">
                        {{ $cuentaData['codigo'] }} — {{ $cuentaData['nombre'] }}
                    </h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left font-medium text-gray-600">Fecha</th>
                                <th class="px-4 py-2 text-left font-medium text-gray-600">Comprobante</th>
                                <th class="px-4 py-2 text-left font-medium text-gray-600">Descripción</th>
                                <th class="px-4 py-2 text-right font-medium text-gray-600">Debe</th>
                                <th class="px-4 py-2 text-right font-medium text-gray-600">Haber</th>
                                <th class="px-4 py-2 text-right font-medium text-gray-600">Saldo</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @php $saldo = 0; @endphp
                            @foreach ($cuentaData['movimientos'] as $mov)
                                @php
                                    $saldo += $mov->debe - $mov->haber;
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 text-gray-700">
                                        {{ \Carbon\Carbon::parse($mov->comprobante->fecha)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 py-2 text-gray-700">
                                        {{ $mov->comprobante->numero }}
                                    </td>
                                    <td class="px-4 py-2 text-gray-700">
                                        {{ $mov->descripcion }}
                                    </td>
                                    <td class="px-4 py-2 text-right text-gray-700">
                                        {{ number_format($mov->debe, 2) }}
                                    </td>
                                    <td class="px-4 py-2 text-right text-gray-700">
                                        {{ number_format($mov->haber, 2) }}
                                    </td>
                                    <td class="px-4 py-2 text-right font-medium text-gray-900">
                                        {{ number_format($saldo, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <th colspan="3" class="px-4 py-2 text-right font-semibold text-gray-700">Totales</th>
                                <th class="px-4 py-2 text-right font-semibold text-gray-700">
                                    {{ number_format($cuentaData['totales']['debe'], 2) }}
                                </th>
                                <th class="px-4 py-2 text-right font-semibold text-gray-700">
                                    {{ number_format($cuentaData['totales']['haber'], 2) }}
                                </th>
                                <th class="px-4 py-2 text-right font-bold text-gray-900">
                                    {{ number_format($saldo, 2) }}
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        @endforeach
    </div>
@endsection
