@extends('layouts.admin')

@section('content')
    <div class="bg-[var(--header-bg)] p-8">
        <div class="flex flex-wrap">
            <div class="w-full">
                <div class="flex justify-between items-center">
                    <h3 class="text-white text-2xl font-semibold">Gestionar Comprobantes</h3>
                    <div>
                        <!-- Botón para crear un comprobante -->
                        <a href="{{ route('show.comprobantes.create') }}"
                            class="bg-white text-gray-800 px-4 py-2 rounded mx-1">Nuevo Comprobante</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-6">
        <!-- Formulario para filtros y búsqueda -->
        <div class="flex mt-4">
            <form method="GET" action="{{ route('show.comprobantes.home') }}"
                class="flex flex-wrap items-end gap-4 bg-white p-4 rounded-lg shadow mb-6 w-full">

                <div>
                    <label for="fecha" class="block text-sm font-medium text-gray-700">Fecha</label>
                    <input type="date" id="fecha" name="fecha" value="{{ request('fecha') }}"
                        class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                </div>

                <div>
                    <label for="tipo_comprobante" class="block text-sm font-medium text-gray-700">Tipo</label>
                    <select id="tipo_comprobante" name="tipo_comprobante"
                        class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        <option value="" disabled @if (!request('tipo_comprobante')) selected @endif>Seleccione
                        </option>
                        <option value="todos" @selected(request('tipo_comprobante') == 'todos')>Todos</option>
                        <option value="ingreso" @selected(request('tipo_comprobante') == 'ingreso')>Ingreso</option>
                        <option value="egreso" @selected(request('tipo_comprobante') == 'egreso')>Egreso</option>
                        <option value="traspaso" @selected(request('tipo_comprobante') == 'traspaso')>Traspaso</option>
                        <option value="ajuste" @selected(request('tipo_comprobante') == 'ajuste')>Ajuste</option>
                    </select>
                </div>

                <div class="flex-1">
                    <label for="glosa_general" class="block text-sm font-medium text-gray-700">Glosa</label>
                    <input type="text" id="glosa_general" name="glosa_general" value="{{ request('glosa_general') }}"
                        placeholder="Buscar por glosa"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-[var(--header-bg)] text-white text-sm font-medium rounded-md shadow hover:bg-blue-700">
                        Buscar
                    </button>
                    <a href="{{ route('show.comprobantes.home') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-800 text-sm font-medium rounded-md shadow hover:bg-gray-200">
                        Limpiar filtros
                    </a>
                </div>
            </form>
        </div>

        <!-- Tabla de comprobantes -->
        <div class="flex flex-wrap mt-6">
            <div class="w-full">
                <div class="bg-white rounded-lg shadow">
                    <div class="p-4">
                        <div class="overflow-x-auto">
                            <table class="min-w-full border">
                                <thead>
                                    <tr class="bg-[var(--header-bg)] text-white">
                                        <th class="border p-2">Numero de comprobante</th>
                                        <th class="border p-2">Fecha</th>
                                        <th class="border p-2">Tipo</th>
                                        <th class="border p-2">Glosa</th>
                                        <th class="border p-2">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($comprobantes as $comprobante)
                                        <tr>
                                            <td class="border p-2">{{ $comprobante->numero }}</td>
                                            <td class="border p-2">{{ $comprobante->fecha }}</td>
                                            <td class="border p-2">{{ $comprobante->tipo }}</td>
                                            <td class="border p-2">{{ $comprobante->descripcion }}</td>
                                            <td class="border p-2">
                                                <a href="{{ route('show.comprobantes.detail', $comprobante->id) }}"
                                                    class="bg-blue-500 text-white px-3 py-1 rounded text-sm">Detalles</a>
                                                <a href="{{ route('show.comprobantes.edit', $comprobante->id) }}"
                                                    class="bg-yellow-500 text-white px-3 py-1 rounded text-sm">Editar</a>
                                                <form action="{{ route('comprobantes.destroy', $comprobante->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('¿Estás seguro de que deseas eliminar este comprobante?');"
                                                    style="display: inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:underline">Eliminar</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="border p-2 text-center text-gray-500 italic">
                                                @if (!request()->filled('fecha') && !request()->filled('tipo_comprobante') && !request()->filled('glosa_general'))
                                                    Coloca algún filtro para buscar comprobantes.
                                                @else
                                                    No se encontraron comprobantes con los filtros seleccionados.
                                                @endif
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación -->
                        @if ($comprobantes->count() > 0)
                            <div class="mt-4">
                                {{ $comprobantes->appends(request()->query())->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mensaje de éxito -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (session('success'))
        <script>
            Swal.fire({
                title: "¡Éxito!",
                text: "{{ session('success') }}",
                icon: "success",
                confirmButtonText: "OK"
            });
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        </script>
    @endif
@endsection
