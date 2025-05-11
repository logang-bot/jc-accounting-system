@extends('layouts.auth')

@section('content')
    <style>
        .table-fixed-height {
            display: block;
            height: 300px;
            overflow-y: auto;
            width: 100%;
        }

        .table-fixed-height thead,
        .table-fixed-height tbody tr {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        .table-fixed-height thead {
            background-color: #f2f2f2;
            color: #333;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        .table-fixed-height th,
        .table-fixed-height td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
    </style>
    <div class="container d-flex flex-column align-items-center justify-content-center min-vh-100">
        <div class="col-12 col-md-12 col-lg-10 col-xxl-8">
            <div class="card smooth-shadow-md">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6 text-center d-flex flex-column align-items-center justify-content-center">
                            <h1 class="mb-3"
                                style="font-family: 'Arial Black', sans-serif; font-size: 60px; color: #1c3552; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2); letter-spacing: 1px;">
                                Sistema Contable
                            </h1>
                            <img src="{{ asset('/admin_assets/images/ESCUDO.png') }}" alt="Logo" style="height: 300px;">
                            <h2 class="mb-3"
                                style="font-family: 'Arial Black', sans-serif; font-size: 60px; color: #1c3552; text-shadow: 2x 2px 4px rgba(0, 0, 0, 0.2); letter-spacing: 1px;">
                                U A T F
                            </h2>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-center mb-3 w-100">
                                <button type="button" class="btn btn-primary mx-2 w-100" data-bs-toggle="modal"
                                    data-bs-target="#crearEmpresaModal">
                                    {{ __('Crear Empresa') }}
                                </button>
                            </div>
                            <div class="table-fixed-height">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Nombre') }}</th>
                                            <th>{{ __('Opciones') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($empresas as $empresa)
                                            <tr class="hover-highlight" data-empresa-id="{{ $empresa->id }}">
                                                <td>{{ $empresa->name }}</td>
                                                <td>
                                                    <a href="{{ route('show.empresa.home', $empresa->id) }}"
                                                        class="btn btn-info btn-sm">Ingresar</a>
                                                    <button onclick="confirmDelete({{ $empresa->id }})"
                                                        class="btn btn-danger btn-sm">Eliminar</button>
                                                    <form id="delete-form-{{ $empresa->id }}"
                                                        action="{{ route('empresas.destroy', $empresa->id) }}"
                                                        method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-danger w-100">{{ __('Cerrar Sesión') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="crearEmpresaModal" tabindex="-1" aria-labelledby="crearEmpresaModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="crearEmpresaModalLabel">{{ __('Crear Empresa') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="crearEmpresaForm" method="POST" action="{{ route('empresas.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="empresaName" class="form-label">{{ __('Nombre') }}</label>
                            <input id="empresaName" type="text" class="form-control" name="name" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">{{ __('Crear') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${id}`).submit();
                }
            });
        }

        // Mostrar SweetAlert2 para mensajes de éxito
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: "{{ session('success') }}",
                confirmButtonColor: '#3085d6'
            });
        @endif
    </script>
@endsection
