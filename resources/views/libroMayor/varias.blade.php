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
    <div class="p-8 bg-gray-100 min-h-screen">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            {{-- Filtros --}}
            <form action="{{ route('show.libro-mayor.varias') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    {{-- Rango de Cuentas --}}
                    <div class="flex items-center space-x-2">
                        <label class="w-32 font-medium">Desde Cuenta:</label>
                        <input type="text" name="cuenta_desde" placeholder="0000" class="border rounded p-2 w-full" />
                    </div>

                    <div class="flex items-center space-x-2">
                        <label class="w-32 font-medium">Hasta Cuenta:</label>
                        <input type="text" name="cuenta_hasta" placeholder="9999" class="border rounded p-2 w-full" />
                    </div>

                    {{-- Rango de Fechas --}}
                    <div class="flex items-center space-x-2">
                        <label class="w-32 font-medium">Fecha Desde:</label>
                        <input type="date" name="fecha_desde" class="border rounded p-2 w-full" />
                    </div>

                    <div class="flex items-center space-x-2">
                        <label class="w-32 font-medium">Fecha Hasta:</label>
                        <input type="date" name="fecha_hasta" class="border rounded p-2 w-full" />
                    </div>

                    {{-- Moneda --}}
                    <div class="flex items-center space-x-2">
                        <label class="w-32 font-medium">Moneda:</label>
                        <select name="moneda" class="border rounded p-2 w-full">
                            <option value="bs">Bs.</option>
                            <option value="usd">US$</option>
                            <option value="ambas">Bs. y US$</option>
                        </select>
                    </div>

                    {{-- Tipo de impresión --}}
                    {{-- <div class="flex items-center space-x-2">
                        <label class="w-32 font-medium">Tipo de impresión:</label>
                        <select name="tipo_impresion" class="border rounded p-2 w-full">
                            <option value="varias_hoja">Varias cuentas en una hoja</option>
                            <option value="mayor_hoja">Un mayor por hoja</option>
                        </select>
                    </div> --}}
                </div>

                {{-- Botones de acción --}}
                <div class="mt-6 flex flex-wrap gap-4">
                    <button type="submit" name="accion" value="pdf"
                        class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                        Generar PDF
                    </button>
                    <a href="{{ route('show.libro-mayor.varias') }}"
                        class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>

        {{-- Tabla de resultados --}}
        @if (isset($resultados) && count($resultados) > 0)
            <div class="mt-8 bg-white p-6 rounded-lg shadow-lg overflow-x-auto">
                <table class="w-full table-auto border border-gray-300">
                    <thead>
                        <tr class="bg-gray-200 text-left">
                            <th class="border px-2 py-1">Cuenta</th>
                            <th class="border px-2 py-1">Nombre</th>
                            <th class="border px-2 py-1">Debe (Bs.)</th>
                            <th class="border px-2 py-1">Haber (Bs.)</th>
                            <th class="border px-2 py-1">Saldo (Bs.)</th>
                            @if ($moneda == 'usd' || $moneda == 'ambas')
                                <th class="border px-2 py-1">Debe (US$)</th>
                                <th class="border px-2 py-1">Haber (US$)</th>
                                <th class="border px-2 py-1">Saldo (US$)</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($resultados as $cuenta)
                            <tr>
                                <td class="border px-2 py-1">{{ $cuenta->codigo }}</td>
                                <td class="border px-2 py-1">{{ $cuenta->nombre }}</td>
                                <td class="border px-2 py-1">{{ number_format($cuenta->debe_bs, 2) }}</td>
                                <td class="border px-2 py-1">{{ number_format($cuenta->haber_bs, 2) }}</td>
                                <td class="border px-2 py-1">{{ number_format($cuenta->saldo_bs, 2) }}</td>
                                @if ($moneda == 'usd' || $moneda == 'ambas')
                                    <td class="border px-2 py-1">{{ number_format($cuenta->debe_usd, 2) }}</td>
                                    <td class="border px-2 py-1">{{ number_format($cuenta->haber_usd, 2) }}</td>
                                    <td class="border px-2 py-1">{{ number_format($cuenta->saldo_usd, 2) }}</td>
                                @endif
                            </tr>
                        @endforeach
                        {{-- Totales generales --}}
                        <tr class="font-bold bg-gray-100">
                            <td colspan="2" class="border px-2 py-1 text-right">Totales:</td>
                            <td class="border px-2 py-1">{{ number_format($totales->debe_bs, 2) }}</td>
                            <td class="border px-2 py-1">{{ number_format($totales->haber_bs, 2) }}</td>
                            <td class="border px-2 py-1">{{ number_format($totales->saldo_bs, 2) }}</td>
                            @if ($moneda == 'usd' || $moneda == 'ambas')
                                <td class="border px-2 py-1">{{ number_format($totales->debe_usd, 2) }}</td>
                                <td class="border px-2 py-1">{{ number_format($totales->haber_usd, 2) }}</td>
                                <td class="border px-2 py-1">{{ number_format($totales->saldo_usd, 2) }}</td>
                            @endif
                        </tr>
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
