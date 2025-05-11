@extends('layouts.admin')

@section('content')
    <div class="bg-primary pt-10 pb-21"></div>
    <div class="container-fluid mt-n22 px-6">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="mb-0 text-white">Empresa {{ $empresa->name ?? 'No definida' }}</h3>
                </div>
            </div>
        </div>
        <div class="row mt-6">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-white py-4">
                        <h4 class="mb-0">Datos de la empresa</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('empresa.update', $empresa->id ?? 0) }}" method="POST">
                            @csrf
                            @method('PUT')

                            @php $datos = $empresa->datoEmpresa ?? null; @endphp

                            <!-- Nombre y NIT en la misma fila -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                           value="{{ old('name', $empresa->name ?? '') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="nit" class="form-label">NIT</label>
                                    <input type="text" class="form-control" id="nit" name="nit"
                                           value="{{ old('nit', $datos->nit ?? '') }}" required>
                                </div>
                            </div>

                            <!-- Ciudad y Provincia en la misma fila -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="ciudad" class="form-label">Ciudad</label>
                                    <input type="text" class="form-control" id="ciudad" name="ciudad"
                                           value="{{ old('ciudad', $datos->ciudad ?? '') }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="provincia" class="form-label">Provincia</label>
                                    <input type="text" class="form-control" id="provincia" name="provincia"
                                           value="{{ old('provincia', $datos->provincia ?? '') }}">
                                </div>
                            </div>

                            <!-- Dirección (fila independiente) -->
                            <div class="mb-3">
                                <label for="direccion" class="form-label">Dirección</label>
                                <input type="text" class="form-control" id="direccion" name="direccion"
                                       value="{{ old('direccion', $datos->direccion ?? '') }}">
                            </div>

                            <!-- Teléfono y Celular en la misma fila -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="celular" class="form-label">Celular</label>
                                    <input type="text" class="form-control" id="celular" name="celular"
                                           value="{{ old('celular', $datos->celular ?? '') }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="telefono" class="form-label">Teléfono</label>
                                    <input type="text" class="form-control" id="telefono" name="telefono"
                                           value="{{ old('telefono', $datos->telefono ?? '') }}">
                                </div>
                            </div>

                            <!-- Correo Electrónico -->
                            <div class="mb-3">
                                <label for="correo_electronico" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="correo_electronico" name="correo_electronico"
                                       value="{{ old('correo_electronico', $datos->correo_electronico ?? '') }}">
                            </div>

                            <!-- Periodo y Gestión en la misma fila -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="gestion" class="form-label">Gestión</label>
                                    <input type="number" class="form-control" id="gestion" name="gestion"
                                           value="{{ old('gestion', $datos->gestion ?? '') }}" min="1999" max="2100" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="periodo" class="form-label">Periodo</label>
                                    <select class="form-control" id="periodo" name="periodo" required>
                                        @foreach ([
                                            'Enero - Diciembre (Comercial)',
                                            'Abril - Marzo (Industrial)',
                                            'Junio - Julio (Agropecuaria)',
                                            'Octubre - Septiembre (Minera)'
                                        ] as $periodo)
                                            <option value="{{ $periodo }}" {{ old('periodo', $datos->periodo ?? '') == $periodo ? 'selected' : '' }}>
                                                {{ $periodo }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if(session('success'))
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
