@extends('layouts.admin')

@section('content')
    <div class="bg-primary pt-10 pb-21"></div>
    <div class="container-fluid mt-n22 px-6">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="mb-0 text-white">Plan de Cuentas</h3>
                    <div>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                            data-bs-target="#modalCrearCuenta">
                            Adicionar
                        </button>

                        <button id="btnEditar" type="button" class="btn btn-warning" onclick="abrirModalEditarDesdeBoton()"
                            disabled>
                            Editar
                        </button>

                        <button id="btnBorrar" type="button" class="btn btn-danger" onclick="abrirModalBorrar()" disabled>
                            Borrar
                        </button>

                        <button type="button" class="btn btn-info" data-bs-toggle="modal"
                            data-bs-target="#modalReporteCuenta">
                            Reporte
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla -->
        <div class="row mt-6">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-white py-4">
                        <h4 class="mb-0">Lista de Cuentas</h4>
                    </div>
                    <div class="card-body">
                        <div style="max-height: 560px; overflow-y: auto; border: 1px solid #ddd;">
                            <table class="table table-bordered" id="tablaCuentas">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th>Tipo</th>
                                        <th>Nivel</th>
                                        <th>Movimiento</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($cuentas as $cuenta)
                                        <tr id="cuenta-{{ $cuenta->id_cuenta }}" class="cuenta-row"
                                            data-id="{{ $cuenta->id_cuenta }}" 
                                            data-codigo="{{ $cuenta->codigo_cuenta }}"
                                            data-nombre="{{ $cuenta->nombre_cuenta }}"
                                            data-tipo="{{ $cuenta->tipo_cuenta }}" 
                                            data-nivel="{{ $cuenta->nivel }}"
                                            onclick="seleccionarCuenta({{ $cuenta->id_cuenta }}, '{{ $cuenta->codigo_cuenta }}', '{{ $cuenta->nombre_cuenta }}', '{{ $cuenta->tipo_cuenta }}', '{{ $cuenta->nivel }}')">
                                            <td style="width: 150px; font-family: 'Arial', monospace;">
                                                {{ $cuenta->codigo_cuenta }}
                                            </td>
                                            <td>{{ str_repeat('— ', min($cuenta->nivel - 1, 4)) . $cuenta->nombre_cuenta }}
                                            </td>
                                            <td>{{ $cuenta->tipo_cuenta }}</td>
                                            <td>{{ $cuenta->nivel }}</td>
                                            <td>
                                                @if ($cuenta->es_movimiento)
                                                    <span class="badge bg-success">Sí</span>
                                                @else
                                                    <span class="badge bg-secondary">No</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button class="btn btn-warning btn-editar"
                                                    data-id="{{ $cuenta->id_cuenta }}"
                                                    data-codigo="{{ $cuenta->codigo_cuenta }}"
                                                    data-nombre="{{ $cuenta->nombre_cuenta }}"
                                                    data-tipo="{{ $cuenta->tipo_cuenta }}"
                                                    data-nivel="{{ $cuenta->nivel }}" onclick="abrirModalEditar(this)">
                                                    Editar
                                                </button>
                                            </td>
                                        </tr>

                                        @if ($cuenta->children->isNotEmpty())
                                            @foreach ($cuenta->children as $child)
                                                @include('cuentas.partials.fila_cuenta', [
                                                    'cuenta' => $child,
                                                    'nivel' => $cuenta->nivel + 1,
                                                ])
                                            @endforeach
                                        @endif
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No hay cuentas registradas</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modales -->
        @include('cuentas.crud.create')
        @include('cuentas.crud.edit')
        @include('cuentas.crud.delete')
        @include('cuentas.crud.report')

        <!-- seleccionar cuenta -->
        <script>
            let cuentaSeleccionadaId = null;
            let cuentaSeleccionadaCodigo = null;
            let cuentaSeleccionadaNombre = null;
            let cuentaSeleccionadaTipo = null;
            let cuentaSeleccionadaNivel = null;
            let filaSeleccionada = null; // Nueva variable para la fila seleccionada

            // Función para manejar la selección de una cuenta
            function seleccionarCuenta(id, codigo, nombre, tipo, nivel) {
                cuentaSeleccionadaId = id;
                cuentaSeleccionadaCodigo = codigo;
                cuentaSeleccionadaNombre = nombre;
                cuentaSeleccionadaTipo = tipo;
                cuentaSeleccionadaNivel = nivel;

                // Remover selección anterior
                if (filaSeleccionada) {
                    filaSeleccionada.classList.remove("seleccionada");
                }

                // Aplicar selección a la nueva fila
                filaSeleccionada = document.getElementById(`cuenta-${id}`);
                if (filaSeleccionada) {
                    filaSeleccionada.classList.add("seleccionada"); // Fila seleccionada mantiene el sombreado
                }

                // Habilitar botones de Editar y Borrar
                document.getElementById("btnEditar").removeAttribute("disabled");
                document.getElementById("btnBorrar").removeAttribute("disabled");
            }

            // Función para abrir el modal de edición con los datos de la cuenta seleccionada
            function abrirModalEditarSeleccionado() {
                if (cuentaSeleccionadaId) {
                    document.getElementById('idCuentaEditar').value = cuentaSeleccionadaId;
                    document.getElementById('codigoCuentaEditar').value = cuentaSeleccionadaCodigo;
                    document.getElementById('nombreCuentaEditar').value = cuentaSeleccionadaNombre;
                    document.getElementById('tipoCuentaEditar').value = cuentaSeleccionadaTipo;
                    document.getElementById('nivelCuentaEditar').value = cuentaSeleccionadaNivel;

                    let modalEditar = new bootstrap.Modal(document.getElementById('modalEditarCuenta'));
                    modalEditar.show();
                }
            }

            // Control de hover y clic en las filas
            document.addEventListener("DOMContentLoaded", function() {
                document.querySelectorAll(".cuenta-row").forEach(item => {
                    // Hover: resaltar fila al pasar el mouse
                    item.addEventListener("mouseenter", function() {
                        if (!this.classList.contains("seleccionada")) {
                            this.classList.add("table-secondary"); // Resaltar cuando el mouse pasa
                        }
                    });

                    // Hover: quitar el resalto al salir del mouse
                    item.addEventListener("mouseleave", function() {
                        if (!this.classList.contains("seleccionada")) {
                            this.classList.remove(
                                "table-secondary"); // Quitar resalto solo si no está seleccionada
                        }
                    });

                    // Clic en fila para seleccionar
                    item.addEventListener("click", function() {
                        let id = this.dataset.id;
                        let codigo = this.dataset.codigo;
                        let nombre = this.dataset.nombre;
                        let tipo = this.dataset.tipo;
                        let nivel = this.dataset.nivel;

                        seleccionarCuenta(id, codigo, nombre, tipo,
                            nivel); // Llamar para seleccionar la fila
                    });
                });
            });

            // Función para abrir el modal de edición cuando se hace clic en el botón
            function abrirModalEditar(button) {
                const idCuenta = button.getAttribute('data-id');
                const codigoCuenta = button.getAttribute('data-codigo');
                const nombreCuenta = button.getAttribute('data-nombre');
                const tipoCuenta = button.getAttribute('data-tipo');
                const nivelCuenta = button.getAttribute('data-nivel');

                document.getElementById('idCuentaEditar').value = idCuenta;
                document.getElementById('codigoCuentaEditar').value = codigoCuenta;
                document.getElementById('nombreCuentaEditar').value = nombreCuenta;
                document.getElementById('tipoCuentaEditar').value = tipoCuenta;
                document.getElementById('nivelCuentaEditar').value = nivelCuenta;

                let modalEditar = new bootstrap.Modal(document.getElementById('modalEditarCuenta'));
                modalEditar.show();
            }
        </script>
    </div>
@endsection
