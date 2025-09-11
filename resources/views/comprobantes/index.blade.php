@extends('layouts.admin')

@section('content')
    <div class="bg-blue-600 p-8">
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
        <div class="flex flex-wrap mt-4">
            <div class="w-full">
                <form method="GET" action="{{ route('show.comprobantes.home') }}">
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-4">
                            <div class="flex flex-col">
                                <!-- Filtro por tipo de comprobante (dropdown) -->
                                <div class="w-full md:w-1/4 mb-3 pr-2">
                                    <select name="tipo_comprobante" class="w-full p-2 border rounded">
                                        <option value="">Todos los tipos</option>
                                        <option value="ingreso"
                                            {{ request('tipo_comprobante') == 'ingreso' ? 'selected' : '' }}>Ingreso
                                        </option>
                                        <option value="egreso"
                                            {{ request('tipo_comprobante') == 'egreso' ? 'selected' : '' }}>Egreso</option>
                                        <option value="traspaso"
                                            {{ request('tipo_comprobante') == 'traspaso' ? 'selected' : '' }}>Traspaso
                                        </option>
                                        <option value="ajuste"
                                            {{ request('tipo_comprobante') == 'ajuste' ? 'selected' : '' }}>Ajuste</option>
                                    </select>
                                </div>

                                <div class="flex flex-row">
                                    <!-- Filtro por fecha -->
                                    <div class="w-full md:w-1/4 mb-3 pr-2">
                                        <input type="date" name="fecha" class="w-full p-2 border rounded"
                                            placeholder="Filtrar por fecha" value="{{ request('fecha') }}">
                                    </div>

                                    <!-- Filtro por glosa -->
                                    <div class="w-full md:w-1/4 mb-3 pr-2">
                                        <input type="text" name="glosa_general" class="w-full p-2 border rounded"
                                            placeholder="Buscar por glosa" value="{{ request('glosa_general') }}">
                                    </div>
                                </div>

                                <!-- Botón para aplicar filtros -->
                                <div class="w-full md:w-1/4 mb-3">
                                    <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded">Buscar</button>
                                    <a href="{{ route('show.comprobantes.home') }}"
                                        class="text-blue-600 underline whitespace-nowrap self-center">Limpiar filtros</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabla de comprobantes -->
        <div class="flex flex-wrap mt-6">
            <div class="w-full">
                <div class="bg-white rounded-lg shadow">
                    <div class="p-4">
                        <div class="overflow-x-auto">
                            <table class="min-w-full border">
                                <thead>
                                    <tr class="bg-blue-600 text-white">
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
                                                No hay comprobantes registrados.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación -->
                        <div class="mt-4">
                            {{ $comprobantes->appends(request()->query())->links() }}
                        </div>
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
