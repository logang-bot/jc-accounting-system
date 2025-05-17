@extends('layouts.admin')

@section('content')
    <div class="bg-white h-screen">
        <div class="bg-blue-600 pt-10 pb-[84px]"></div>
        <div class="mt-[-88px] px-6 w-full max-w-screen-xl mx-auto">
            <div class="flex flex-col gap-6">
                <!-- Header Actions -->
                <div class="flex justify-between items-center">
                    <h3 class="text-white text-2xl font-semibold">Plan de Cuentas</h3>
                    <div class="flex gap-2 flex-wrap">
                        <button type="button" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
                            aria-controls="cuentas-create" data-hs-overlay="#cuentas-create">
                            Adicionar
                        </button>
                        <button id="btnEditar" type="button"
                            class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 disabled:opacity-50"
                            aria-controls="cuentas-edit" data-hs-overlay="#cuentas-edit" disabled>
                            Editar
                        </button>
                        <button id="btnBorrar" type="button"
                            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 disabled:opacity-50"
                            aria-controls="cuentas-delete" data-hs-overlay="#cuentas-delete" disabled>
                            Borrar
                        </button>
                        <button type="button" class="bg-cyan-600 text-white px-4 py-2 rounded hover:bg-cyan-700"
                            aria-controls="cuentas-report" data-hs-overlay="#cuentas-report">
                            Reporte
                        </button>
                    </div>
                </div>

                <!-- Table -->
                <div class="bg-white rounded shadow">
                    <div class="border-b px-6 py-4">
                        <h4 class="text-lg font-semibold">Lista de Cuentas</h4>
                    </div>
                    <div class="p-4 overflow-y-auto" style="max-height: 560px;">

                        <div class="hs-accordion-group space-y-1 border border-gray-300 rounded-md divide-y divide-gray-300"
                            data-hs-accordion-always-open>

                            <!-- Header row -->
                            <div class="grid grid-cols-6 bg-gray-800 text-white text-sm font-semibold">
                                <div class="px-4 py-2 border-r">Código</div>
                                <div class="px-4 py-2 border-r">Nombre</div>
                                <div class="px-4 py-2 border-r">Tipo</div>
                                <div class="px-4 py-2 border-r">Nivel</div>
                                <div class="px-4 py-2 border-r">Movimiento</div>
                                <div class="px-4 py-2">Acciones</div>
                            </div>

                            <!-- Content rows -->
                            @forelse ($cuentas as $cuenta)
                                <div class="hs-accordion" id="cuenta-{{ $cuenta->id_cuenta }}">
                                    <div class="grid grid-cols-6 items-center text-sm cursor-pointer hover:bg-gray-100 px-4 py-2"
                                        onclick="seleccionarCuenta({{ $cuenta->id_cuenta }}, '{{ $cuenta->codigo_cuenta }}', '{{ $cuenta->nombre_cuenta }}', '{{ $cuenta->tipo_cuenta }}', '{{ $cuenta->nivel }}')">
                                        <div class="font-mono">{{ $cuenta->codigo_cuenta }}</div>
                                        <div>
                                            {{ str_repeat('— ', min($cuenta->nivel - 1, 4)) . $cuenta->nombre_cuenta }}
                                        </div>
                                        <div>{{ $cuenta->tipo_cuenta }}</div>
                                        <div>Nivel {{ $cuenta->nivel }}</div>
                                        <div>
                                            @if ($cuenta->es_movimiento)
                                                <span
                                                    class="inline-block bg-green-600 text-white text-xs font-semibold px-2 py-1 rounded">Sí</span>
                                            @else
                                                <span
                                                    class="inline-block bg-gray-500 text-white text-xs font-semibold px-2 py-1 rounded">No</span>
                                            @endif
                                        </div>
                                        <div class="flex gap-2">
                                            <button class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded"
                                                onclick="event.stopPropagation(); abrirModalEditar(this)"
                                                data-id="{{ $cuenta->id_cuenta }}"
                                                data-codigo="{{ $cuenta->codigo_cuenta }}"
                                                data-nombre="{{ $cuenta->nombre_cuenta }}"
                                                data-tipo="{{ $cuenta->tipo_cuenta }}" data-nivel="{{ $cuenta->nivel }}">
                                                Editar
                                            </button>
                                            @if ($cuenta->children->isNotEmpty())
                                                <button type="button"
                                                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded hs-accordion-toggle"
                                                    aria-expanded="true"
                                                    aria-controls="cuenta-accordion-{{ $cuenta->id_cuenta }}">
                                                    Expandir
                                                </button>
                                            @endif
                                        </div>
                                    </div>

                                    @if ($cuenta->children->isNotEmpty())
                                        <div id="cuenta-accordion-{{ $cuenta->id_cuenta }}"
                                            class="hs-accordion-content hidden pl-4 border-t border-gray-200"
                                            aria-labelledby="cuenta-{{ $cuenta->id_cuenta }}">
                                            <div class="hs-accordion-group" data-hs-accordion-always-open>
                                                @foreach ($cuenta->children as $child)
                                                    @include('cuentas.partials.fila_cuenta', [
                                                        'cuenta' => $child,
                                                        'nivel' => $cuenta->nivel + 1,
                                                    ])
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="text-center text-gray-500 py-4">No hay cuentas registradas</div>
                            @endforelse
                        </div>


                    </div>
                </div>
            </div>

            <x-modal id="cuentas-create">
                @include('cuentas.crud.create')
            </x-modal>

            <x-modal id="cuentas-edit">
                @include('cuentas.crud.edit')
            </x-modal>

            <x-modal id="cuentas-delete">
                @include('cuentas.crud.delete')
            </x-modal>

            <x-modal id="cuentas-report">
                @include('cuentas.crud.report')
            </x-modal>

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

                        // let modalEditar = new bootstrap.Modal(document.getElementById('modalEditarCuenta'));
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

                    // let modalEditar = new bootstrap.Modal(document.getElementById('modalEditarCuenta'));
                    modalEditar.show();
                }
            </script>
        </div>

    </div>
@endsection
