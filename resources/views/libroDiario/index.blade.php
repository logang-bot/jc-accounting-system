@extends('layouts.admin')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        <!-- Título -->
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Libro Diario</h1>

        <!-- Filtros -->
        <form method="GET" class="grid grid-cols-1 sm:grid-cols-5 gap-4 bg-white p-4 rounded-lg shadow mb-6">
            <div>
                <label for="fecha_desde" class="block text-sm font-medium text-gray-700">Fecha desde</label>
                <input type="date" id="fecha_desde" name="fecha_desde" value="{{ request('fecha_desde') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
            </div>

            <div>
                <label for="fecha_hasta" class="block text-sm font-medium text-gray-700">Fecha hasta</label>
                <input type="date" id="fecha_hasta" name="fecha_hasta" value="{{ request('fecha_hasta') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
            </div>

            <div>
                <label for="empresa_id" class="block text-sm font-medium text-gray-700">Empresa</label>
                <select id="empresa_id" name="empresa_id"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    <option value="">Todas</option>
                    @foreach (\App\Models\Empresa::all() as $empresa)
                        <option value="{{ $empresa->id }}" @selected(request('empresa_id') == $empresa->id)>{{ $empresa->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="tipo" class="block text-sm font-medium text-gray-700">Tipo</label>
                <select id="tipo" name="tipo"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    <option value="">Todos</option>
                    <option value="ingreso" @selected(request('tipo') == 'ingreso')>Ingreso</option>
                    <option value="egreso" @selected(request('tipo') == 'egreso')>Egreso</option>
                    <option value="ajuste" @selected(request('tipo') == 'ajuste')>Ajuste</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md shadow hover:bg-blue-700">
                    Filtrar
                </button>
                <a href="{{ route('libro-diario.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-800 text-sm font-medium rounded-md shadow hover:bg-gray-200">
                    Limpiar
                </a>
            </div>
        </form>

        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full text-sm text-gray-800">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-2 text-left font-semibold">Fecha</th>
                        <th class="px-4 py-2 text-left font-semibold">Num</th>
                        <th class="px-4 py-2 text-left font-semibold">Tipo</th>
                        <th class="px-4 py-2 text-left font-semibold">Descripción</th>
                        <th class="px-4 py-2 text-left font-semibold">Cuenta</th>
                        <th class="px-4 py-2 text-right font-semibold">Debe</th>
                        <th class="px-4 py-2 text-right font-semibold">Haber</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($comprobantes as $comp)
                        <tr class="bg-blue-50">
                            <td class="px-4 py-2">{{ $comp->fecha }}</td>
                            <td class="px-4 py-2">{{ $comp->numero }}</td>
                            <td class="px-4 py-2 capitalize">{{ $comp->tipo }}</td>
                            <td class="px-4 py-2" colspan="4">{{ $comp->descripcion }}</td>
                        </tr>
                        @foreach ($comp->detalles as $det)
                            <tr class="border-b">
                                <td class="px-4 py-2"></td>
                                <td class="px-4 py-2"></td>
                                <td class="px-4 py-2"></td>
                                <td class="px-4 py-2"></td>
                                <td class="px-4 py-2">{{ $det->cuenta->codigo_cuenta ?? '' }} -
                                    {{ $det->cuenta->nombre_cuenta ?? '' }}</td>
                                <td class="px-4 py-2 text-right">{{ number_format($det->debe ?? 0, 2) }}</td>
                                <td class="px-4 py-2 text-right">{{ number_format($det->haber ?? 0, 2) }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-100 font-semibold">
                    <tr>
                        <td colspan="5" class="px-4 py-2 text-right">Totales (página)</td>
                        <td class="px-4 py-2 text-right">{{ number_format($totales['debe'], 2) }}</td>
                        <td class="px-4 py-2 text-right">{{ number_format($totales['haber'], 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="mt-4">
            {{ $comprobantes->links() }}
        </div>

    </div>
@endsection
