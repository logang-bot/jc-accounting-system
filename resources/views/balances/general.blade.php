@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold mb-6">Balance General (Estado de Situación Financiera)</h1>

        {{-- Filters --}}
        <form method="GET" action="{{ route('balances.general') }}" class="mb-6 flex gap-4 items-end">
            <div>
                <label for="fecha_desde" class="block text-sm font-medium">Desde</label>
                <input type="date" id="fecha_desde" name="fecha_desde" value="{{ $fechaDesde }}"
                    class="border rounded px-2 py-1 w-full">
            </div>

            <div>
                <label for="fecha_hasta" class="block text-sm font-medium">Hasta</label>
                <input type="date" id="fecha_hasta" name="fecha_hasta" value="{{ $fechaHasta }}"
                    class="border rounded px-2 py-1 w-full">
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded shadow">
                Filtrar
            </button>
        </form>

        {{-- Balance Table --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            {{-- Activos --}}
            <div class="bg-white shadow rounded p-4">
                <h2 class="text-xl font-semibold mb-4">Activos</h2>
                <table class="w-full text-sm">
                    <tbody>
                        @foreach ($balances['activos'] ?? [] as $cuenta)
                            @foreach ($cuenta['full_parent_chain'] as $i => $parent)
                                <tr>
                                    <td>
                                        <span class="inline-block" style="padding-left: {{ $i * 20 }}px;">
                                            {{ $parent }}
                                        </span>
                                    </td>
                                    <td></td>
                                </tr>
                            @endforeach

                            <tr>
                                <td>
                                    <span class="inline-block" style="padding-left: {{ $cuenta['nivel'] * 20 }}px;">
                                        {{ $cuenta['codigo_cuenta'] }} - {{ $cuenta['nombre'] }}
                                    </span>
                                </td>
                                <td class="text-right">{{ number_format($cuenta['saldo'], 2) }}</td>
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
                        @foreach ($balances['pasivos'] ?? [] as $cuenta)
                            @foreach ($cuenta['full_parent_chain'] as $i => $parent)
                                <tr>
                                    <td>
                                        <span class="inline-block" style="padding-left: {{ $i * 20 }}px;">
                                            {{ $parent }}
                                        </span>
                                    </td>
                                    <td></td>
                                </tr>
                            @endforeach

                            <tr>
                                <td>
                                    <span class="inline-block" style="padding-left: {{ $cuenta['nivel'] * 20 }}px;">
                                        {{ $cuenta['codigo_cuenta'] }} - {{ $cuenta['nombre'] }}
                                    </span>
                                </td>
                                <td class="text-right">{{ number_format($cuenta['saldo'], 2) }}</td>
                            </tr>
                        @endforeach
                        @foreach ($balances['patrimonio'] ?? [] as $cuenta)
                            @foreach ($cuenta['full_parent_chain'] as $i => $parent)
                                <tr>
                                    <td>
                                        <span class="inline-block" style="padding-left: {{ $i * 20 }}px;">
                                            {{ $parent }}
                                        </span>
                                    </td>
                                    <td></td>
                                </tr>
                            @endforeach

                            <tr>
                                <td>
                                    <span class="inline-block" style="padding-left: {{ $cuenta['nivel'] * 20 }}px;">
                                        {{ $cuenta['codigo_cuenta'] }} - {{ $cuenta['nombre'] }}
                                    </span>
                                </td>
                                <td class="text-right">{{ number_format($cuenta['saldo'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="font-bold border-t">
                            <td>Total Pasivos + Patrimonio</td>
                            <td class="text-right">
                                {{ number_format(($balances['total_pasivos'] ?? 0) + ($balances['total_patrimonio'] ?? 0), 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
