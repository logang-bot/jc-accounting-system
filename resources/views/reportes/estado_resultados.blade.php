@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4">
        <h1 class="text-2xl font-bold mb-4">Estado de Resultados</h1>

        {{-- Filters --}}
        <form method="GET" action="{{ route('estado-resultados.index') }}" class="mb-6 flex gap-4">
            <input type="date" name="fecha_desde" value="{{ $fechaDesde }}" class="border rounded px-2 py-1">
            <input type="date" name="fecha_hasta" value="{{ $fechaHasta }}" class="border rounded px-2 py-1">
            <button type="submit" class="bg-blue-600 text-white px-4 py-1 rounded">Filtrar</button>
        </form>

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
                    @foreach ($resultados['ingresos'] as $cuenta)
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
                        <tr class="border-b">
                            <td>
                                <span class="inline-block" style="padding-left: {{ $cuenta['level'] * 20 }}px;">
                                    {{ $cuenta['codigo_cuenta'] }} - {{ $cuenta['nombre'] }}
                                </span>
                            </td>
                            <td class="text-right">{{ number_format($cuenta['saldo'], 2) }}</td>
                        </tr>
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
                    @foreach ($resultados['egresos'] as $cuenta)
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
                        <tr class="border-b">
                            <td>
                                <span class="inline-block" style="padding-left: {{ $cuenta['level'] * 20 }}px;">
                                    {{ $cuenta['codigo_cuenta'] }} - {{ $cuenta['nombre'] }}
                                </span>
                            </td>
                            <td class="text-right">{{ number_format($cuenta['saldo'], 2) }}</td>
                        </tr>
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
    </div>
@endsection
