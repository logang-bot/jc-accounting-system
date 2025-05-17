@extends('layouts.admin')

@section('content')
    <div class="bg-white h-screen">
        {{-- <div class="bg-blue-600 pt-10 pb-[84px]"></div> --}}
        <div class="w-full mx-auto">
            <div class="flex flex-col gap-6">
                <!-- Header -->
                <div class="flex justify-between items-center bg-blue-600 px-10 py-5">
                    <h3 class="text-white text-2xl font-semibold">Plan de Cuentas</h3>

                </div>

                <!-- Table -->
                <div class="px-6 flex flex-row w-full gap-4">
                    <div class="flex gap-2 flex-col w-[10%]">
                        <button type="button" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
                            aria-controls="cuentas-create" data-hs-overlay="#cuentas-create">
                            Adicionar
                        </button>
                        <button type="button" class="bg-cyan-600 text-white px-4 py-2 rounded hover:bg-cyan-700"
                            aria-controls="cuentas-report" data-hs-overlay="#cuentas-report">
                            Reporte
                        </button>
                    </div>
                    <div class="overflow-y-auto max-h-[560px] w-[90%]">
                        <div class="hs-accordion-group border border-gray-300 divide-y divide-gray-300"
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
                                    <div class="grid grid-cols-6 items-center text-sm cursor-pointer hover:bg-gray-100">
                                        <div class="font-mono px-4 py-3">{{ $cuenta->codigo_cuenta }}</div>
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
                                            <button
                                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded cursor-pointer"
                                                onclick="event.stopPropagation(); abrirModalEditar(this)"
                                                aria-controls="cuentas-edit" data-hs-overlay="#cuentas-edit"
                                                data-id="{{ $cuenta->id_cuenta }}"
                                                data-codigo="{{ $cuenta->codigo_cuenta }}"
                                                data-nombre="{{ $cuenta->nombre_cuenta }}"
                                                data-tipo="{{ $cuenta->tipo_cuenta }}" data-nivel="{{ $cuenta->nivel }}">
                                                Editar
                                            </button>
                                            @if ($cuenta->children->isNotEmpty())
                                                <button type="button"
                                                    class="hover:bg-black/20 px-3 py-1 rounded hs-accordion-toggle cursor-pointer"
                                                    aria-expanded="true"
                                                    aria-controls="cuenta-accordion-{{ $cuenta->id_cuenta }}">
                                                    <x-carbon-chevron-down class="w-4 h-4 ms-auto" />
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
                            @endforelse

                            @if ($cuentas->isEmpty())
                                <div class="text-center text-gray-500 py-4">No hay cuentas registradas</div>
                            @endif
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

                const tiposCuentas = ["Activo", "Pasivo", "Patrimonio", "Ingresos", "Egresos"];
                // Función para abrir el modal de edición cuando se hace clic en el botón
                function abrirModalEditar(button) {
                    const idCuenta = button.getAttribute('data-id');
                    const codigoCuenta = button.getAttribute('data-codigo');
                    const nombreCuenta = button.getAttribute('data-nombre');
                    const tipoCuenta = button.getAttribute('data-tipo');
                    const intTipoCuenta = tiposCuentas.find((el) => el == tipoCuenta);
                    const nivelCuenta = button.getAttribute('data-nivel');

                    document.getElementById('codigoCuentaEditar').value = codigoCuenta;
                    document.getElementById('nombreCuentaEditar').value = nombreCuenta;
                    // document.getElementById('tipoCuentaEditar').value = intTipoCuenta;
                    // document.getElementById('nivelCuentaEditar').value = nivelCuenta;

                    document.getElementById("editTipo" + tipoCuenta.toLowerCase()).checked = true;

                    document.getElementById("editNivel" + nivelCuenta.toLowerCase()).checked = true;

                    // let modalEditar = new bootstrap.Modal(document.getElementById('modalEditarCuenta'));
                    // modalEditar.show();
                }
            </script>
        </div>

    </div>
@endsection
