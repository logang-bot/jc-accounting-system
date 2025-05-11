@extends('layouts.admin')

@section('content')
    <div class="bg-primary pt-10 pb-21"></div>
    <div class="container-fluid mt-n22 px-6">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="mb-0 text-white">Lista de Comprobantes</h3>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0 text-white">Gestionar Comprobantes</h3>
                    <div>
                        <!-- Botón para crear un comprobante -->
                        <a href="{{ route('comprobantes.create') }}" class="btn btn-light mx-1">Nuevo Comprobante</a>

                        <!-- Botón para editar un comprobante -->
                        <a href="{{ route('comprobantes.edit', $comprobante->id_comprobante ?? 0) }}"
                            class="btn btn-warning mx-1">Editar Comprobante</a>

                        <!-- Botón para borrar un comprobante -->
                        <form
                            action="{{ route('comprobantes.destroy', ['comprobante' => $comprobante->id_comprobante ?? 0]) }}"
                            method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger mx-1"
                                onclick="return confirm('¿Está seguro de que desea eliminar este comprobante?')">
                                Borrar Comprobante
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario para filtros y búsqueda -->
        <div class="row mt-4">
            <div class="col-md-12">
                <form method="GET" action="{{ route('comprobantes.index') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <!-- Filtro por fecha -->
                                <div class="col-md-3 mb-3">
                                    <input type="date" name="fecha" class="form-control"
                                        placeholder="Filtrar por fecha" value="{{ request('fecha') }}">
                                </div>

                                <!-- Filtro por tipo de comprobante -->
                                <div class="col-md-3 mb-3">
                                    <input type="text" name="tipo_comprobante" class="form-control"
                                        placeholder="Filtrar por tipo" value="{{ request('tipo_comprobante') }}">
                                </div>

                                <!-- Filtro por glosa -->
                                <div class="col-md-3 mb-3">
                                    <input type="text" name="glosa_general" class="form-control"
                                        placeholder="Buscar por glosa" value="{{ request('glosa_general') }}">
                                </div>

                                <!-- Botón para aplicar filtros -->
                                <div class="col-md-3 mb-3">
                                    <button type="submit" class="btn btn-primary w-100">Buscar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabla de comprobantes -->
        <div class="row mt-6">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-white py-4">
                        <h4 class="mb-0">Detalles de Comprobantes</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="bg-primary text-white">
                                        <th>#</th>
                                        <th>Fecha</th>
                                        <th>Tipo</th>
                                        <th>Glosa</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($comprobantes as $comprobante)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $comprobante->fecha_movimiento }}</td>
                                            <td>{{ $comprobante->tipo_movimiento }}</td>
                                            <td>{{ $comprobante->glosa }}</td>
                                            <td>
                                                <a href="{{ route('comprobantes.show', $comprobante->id_comprobante) }}"
                                                    class="btn btn-info btn-sm">Detalles</a>
                                                <a href="{{ route('comprobantes.edit', $comprobante->id_comprobante) }}"
                                                    class="btn btn-warning btn-sm">Editar</a>
                                                <form
                                                    action="{{ route('comprobantes.destroy', $comprobante->id_comprobante) }}"
                                                    method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('¿Está seguro de eliminar este comprobante?')">Eliminar</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No hay comprobantes registrados.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación -->
                        <div class="mt-4">
                            {{ $comprobantes->links() }}
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
        </script>
    @endif
@endsection
