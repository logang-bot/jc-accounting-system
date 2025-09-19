@php
    $oldDetalles = old('detalles');
@endphp

@extends('layouts.admin')

@section('content')
    <div class="max-w-7xl mx-auto p-6 m-6 bg-white shadow-md rounded-xl">
        <h2 class="text-2xl font-semibold mb-6">
            {{ $editMode ? 'Editar Comprobante' : 'Crear Comprobante' }}
        </h2>

        @if (isset($empresa))
            <div class="mb-4 text-sm text-gray-600">
                Empresa actual: <strong>{{ $empresa->name }}</strong><br>
                NIT: <strong>{{ $empresa->nit }}</strong>
            </div>
        @endif

        <form method="POST"
            action="{{ $editMode ? route('comprobantes.update', $comprobante->id) : route('comprobantes.store') }}">
            @csrf
            @if ($editMode)
                @method('PUT')
            @endif

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo:</label>
                    <select id="tipo" name="tipo" class="w-full border rounded px-3 py-2" required>
                        <option value="">-- Seleccione --</option>
                        @foreach (['ingreso', 'egreso', 'traspaso', 'ajuste'] as $tipo)
                            <option value="{{ $tipo }}"
                                {{ old('tipo', $editMode ? $comprobante->tipo : '') === $tipo ? 'selected' : '' }}>
                                {{ ucfirst($tipo) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha:</label>
                    <input type="date" name="fecha" class="w-full border rounded px-3 py-2"
                        value="{{ old('fecha', $editMode ? $comprobante->fecha : '') }}" required>
                </div>

                <div>
                    <label for="tasa-cambio" class="block text-sm font-medium text-gray-700 mb-1">
                        Tasa de Cambio (Bs/USD)
                    </label>
                    <input type="number" step="0.0001" min="0.0001" id="tasa-cambio" name="tasa_cambio"
                        class="w-full border rounded px-3 py-2" oninput="actualizarConversiones()"
                        value="{{ old('tasa_cambio', $editMode ? $comprobante->tasa_cambio : '') }}" required>
                </div>
                <div>
                    <label for="destinatario" id="label-destinatario" class="block text-sm font-medium text-gray-700 mb-1">
                        Destinatario:
                    </label>
                    <input type="text" name="destinatario" id="destinatario" class="w-full border rounded px-3 py-2"
                        value="{{ old('destinatario', $comprobante->destinatario ?? '') }}" required>
                </div>

                <div>
                    <label for="lugar" class="block text-sm font-medium text-gray-700 mb-1">Lugar:</label>
                    <input type="text" name="lugar" id="lugar" class="w-full border rounded px-3 py-2"
                        value="{{ old('lugar', $comprobante->lugar ?? '') }}" required>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                <textarea name="descripcion" rows="2" class="w-full border rounded px-3 py-2">{{ old('descripcion', $editMode ? $comprobante->descripcion : '') }}</textarea>
            </div>


            <h3 class="text-lg font-semibold mb-2">Detalle del Comprobante</h3>

            <div class="overflow-x-auto mb-6">
                @if (!count($cuentas))
                    <p colspan="5" class="border p-2 text-center text-gray-500 italic">
                        No hay cuentas de movimiento registradas.
                    </p>
                @else
                    <table id="table-detalles" class="min-w-full border rounded text-sm text-left">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-3 py-2"># Cuenta Contable</th>
                                <th class="px-3 py-2">Nombre de cuenta</th>
                                <th class="px-3 py-2">Descripción</th>
                                <th class="px-3 py-2">Debe Bs.</th>
                                <th class="px-3 py-2">Haber Bs.</th>
                                <th class="px-3 py-2">Debe (USD)</th>
                                <th class="px-3 py-2">Haber (USD)</th>
                                <th class="px-3 py-2">IVA</th>
                                <th class="px-3 py-2">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="detalle-rows">
                            @if ($oldDetalles && is_array($oldDetalles) && count($oldDetalles))
                                {{-- Rebuild rows from old input (validation failed case) --}}
                                @foreach ($oldDetalles as $i => $detalle)
                                    <tr>
                                        <td class="px-3 py-2">
                                            <input type="text" name="detalles[{{ $i }}][codigo_cuenta]"
                                                value="{{ $detalle['codigo_cuenta'] ?? '' }}"
                                                class="w-full bg-gray-100 border rounded px-2 py-1 text-sm" readonly>
                                        </td>

                                        <td class="px-3 py-2">
                                            <select name="detalles[{{ $i }}][cuenta_id]"
                                                class="w-full border rounded px-2 py-1 cuenta-nombre-select"
                                                data-index="{{ $i }}" required>
                                                @foreach ($cuentas as $cuenta)
                                                    <option value="{{ $cuenta->id_cuenta }}"
                                                        data-codigo="{{ $cuenta->codigo_cuenta }}"
                                                        {{ (string) ($detalle['cuenta_id'] ?? '') === (string) $cuenta->id_cuenta ? 'selected' : '' }}>
                                                        {{ $cuenta->nombre_cuenta }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>

                                        <td class="px-3 py-2">
                                            <input type="text" name="detalles[{{ $i }}][descripcion]"
                                                class="w-full border rounded px-2 py-1"
                                                value="{{ $detalle['descripcion'] ?? '' }}">
                                            {{-- @error("detalles.$i.descripcion")
                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror --}}
                                        </td>

                                        <td class="px-3 py-2">
                                            <input type="number" step="0.01" name="detalles[{{ $i }}][debe]"
                                                class="w-full text-right border rounded px-2 py-1"
                                                value="{{ $detalle['debe'] ?? '' }}">
                                            {{-- @error("detalles.$i.debe")
                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror --}}
                                        </td>

                                        <td class="px-3 py-2">
                                            <input type="number" step="0.01"
                                                name="detalles[{{ $i }}][haber]"
                                                class="w-full text-right border rounded px-2 py-1"
                                                value="{{ $detalle['haber'] ?? '' }}">
                                            {{-- @error("detalles.$i.haber")
                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror --}}
                                        </td>

                                        <td class="px-3 py-2 text-right us-debe">
                                            <input type="text" readonly
                                                class="w-full text-right bg-gray-100 border rounded px-2 py-1"
                                                value="{{ number_format($detalle['us_debe'] ?? 0, 2) }}">
                                        </td>

                                        <td class="px-3 py-2 text-right us-haber">
                                            <input type="text" readonly
                                                class="w-full text-right bg-gray-100 border rounded px-2 py-1"
                                                value="{{ number_format($detalle['us_haber'] ?? 0, 2) }}">
                                        </td>

                                        <td class="px-3 py-2 text-right iva">
                                            <input type="number" step="0.01" min="0" max="100"
                                                name="detalles[{{ $i }}][iva]"
                                                class="w-20 text-right border rounded px-2 py-1"
                                                value="{{ $detalle['iva'] ?? 0 }}">
                                            {{-- @error("detalles.$i.iva")
                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror --}}
                                        </td>

                                        <td class="px-3 py-2 text-center">
                                            <button type="button" onclick="removeRow(this)"
                                                class="text-red-600 hover:underline">Eliminar</button>
                                        </td>
                                    </tr>
                                @endforeach
                            @elseif ($editMode)
                                @foreach ($comprobante->detalles as $i => $detalle)
                                    <tr>
                                        <td class="px-3 py-2">
                                            <input type="text" name="detalles[{{ $i }}][codigo_cuenta]"
                                                value="{{ $detalle->cuenta->codigo_cuenta }}"
                                                class="w-full bg-gray-100 border rounded px-2 py-1 text-sm" readonly>
                                        </td>

                                        <!-- Columna: Nombre de cuenta (select) -->
                                        <td class="px-3 py-2">
                                            <select name="detalles[{{ $i }}][cuenta_id]"
                                                class="w-full border rounded px-2 py-1 cuenta-nombre-select"
                                                data-index="{{ $i }}" required>
                                                @foreach ($cuentas as $cuenta)
                                                    <option value="{{ $cuenta->id_cuenta }}"
                                                        data-codigo="{{ $cuenta->codigo_cuenta }}"
                                                        {{ $detalle->cuenta_id == $cuenta->id_cuenta ? 'selected' : '' }}>
                                                        {{ $cuenta->nombre_cuenta }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="px-3 py-2">
                                            <input type="text" name="detalles[{{ $i }}][descripcion]"
                                                class="w-full border rounded px-2 py-1"
                                                value="{{ old("detalles.$i.descripcion", $detalle->descripcion ?? '') }}">
                                        </td>
                                        <td class="px-3 py-2">
                                            <input type="number" step="0.01"
                                                name="detalles[{{ $i }}][debe]"
                                                class="w-full text-right border rounded px-2 py-1"
                                                value="{{ old("detalles.$i.debe", $detalle->debe ?? 0) }}">
                                        </td>
                                        <td class="px-3 py-2">
                                            <input type="number" step="0.01"
                                                name="detalles[{{ $i }}][haber]"
                                                class="w-full text-right border rounded px-2 py-1"
                                                value="{{ old("detalles.$i.haber", $detalle->haber ?? 0) }}">
                                        </td>
                                        <td class="px-3 py-2 text-right us-debe">
                                            <input type="text" readonly
                                                class="w-full text-right bg-gray-100 border rounded px-2 py-1"
                                                value="0.00">
                                        </td>
                                        <td class="px-3 py-2 text-right us-haber">
                                            <input type="text" readonly
                                                class="w-full text-right bg-gray-100 border rounded px-2 py-1"
                                                value="0.00">
                                        </td>

                                        <td class="px-3 py-2 text-right iva">
                                            <input type="number" step="0.01" min="0" max="100"
                                                name="detalles[{{ $i }}][iva]"
                                                class="w-20 text-right border rounded px-2 py-1" placeholder="0.00">
                                        </td>

                                        <td class="px-3 py-2 text-center">
                                            <button type="button" onclick="removeRow(this)"
                                                class="text-red-600 hover:underline">Eliminar</button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="px-3 py-2">
                                        <input type="text" name="detalles[0][codigo_cuenta]"
                                            value="{{ $cuentas[0]->codigo_cuenta }}"
                                            class="w-full bg-gray-100 border rounded px-2 py-1 text-sm" readonly>
                                    </td>

                                    <td class="px-3 py-2">
                                        <select name="detalles[0][cuenta_id]"
                                            class="w-full border rounded px-2 py-1 cuenta-nombre-select" data-index="0"
                                            required>
                                            @foreach ($cuentas as $cuenta)
                                                <option value="{{ $cuenta->id_cuenta }}"
                                                    data-codigo="{{ $cuenta->codigo_cuenta }}">
                                                    {{ $cuenta->nombre_cuenta }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-3 py-2">
                                        <input type="text" name="detalles[0][descripcion]"
                                            class="w-full border rounded px-2 py-1">
                                    </td>
                                    <td class="px-3 py-2">
                                        <input type="number" step="0.01" name="detalles[0][debe]"
                                            class="w-full text-right border rounded px-2 py-1">
                                    </td>
                                    <td class="px-3 py-2">
                                        <input type="number" step="0.01" name="detalles[0][haber]"
                                            class="w-full text-right border rounded px-2 py-1">
                                    </td>
                                    <td class="px-3 py-2 text-right us-debe">
                                        <input type="text" readonly
                                            class="w-full text-right bg-gray-100 border rounded px-2 py-1" value="0.00">
                                    </td>
                                    <td class="px-3 py-2 text-right us-haber">
                                        <input type="text" readonly
                                            class="w-full text-right bg-gray-100 border rounded px-2 py-1" value="0.00">
                                    </td>

                                    <td class="px-3 py-2 text-right iva">
                                        <input type="number" step="0.01" min="0" max="100"
                                            name="detalles[0][iva]" class="w-full text-right border rounded px-2 py-1"
                                            placeholder="0.00">
                                    </td>
                                    <td class="px-3 py-2 text-center">
                                        <button type="button" onclick="removeRow(this)"
                                            class="text-red-600 hover:underline">Eliminar</button>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    <section class="flex flex-col">
                        <button type="button" onclick="addRow()"
                            class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Agregar Línea
                        </button>

                        <!-- Botón para abrir modal -->
                        <button type="button" aria-controls="show-plan-cuentas-modal"
                            data-hs-overlay="#show-plan-cuentas-modal"
                            class="mt-4 px-4 py-2 bg-cyan-600 text-white rounded hover:bg-cyan-700">
                            Editar Plan de Cuentas
                        </button>
                    </section>

                @endif
            </div>

            <div class="text-right">
                <button id="submit-button" type="submit"
                    class="px-6 py-2 {{ $editMode ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }} text-white rounded disabled:bg-gray-300 disabled:text-gray-500 disabled:cursor-not-allowed">
                    {{ $editMode ? 'Actualizar Comprobante' : 'Guardar Comprobante' }}
                </button>
            </div>
        </form>
    </div>

    <x-modal id="show-plan-cuentas-modal">
        <div class="flex flex-col gap-2 items-center justify-center min-h-screen px-4">
            <button class="text-white hover:text-gray-700 text-5xl font-bold cursor-pointer"
                data-hs-overlay="#show-plan-cuentas-modal">&times;</button>
            <div class="bg-amber-50 max-w-[90%] p-5">
                @include('cuentas.partials.planCuentas')
            </div>
        </div>
    </x-modal>

    <script>
        function openCuentasModal() {
            document.getElementById('cuentas-modal').classList.remove('hidden');
        }

        function closeCuentasModal() {
            document.getElementById('cuentas-modal').classList.add('hidden');
        }
        document.addEventListener("DOMContentLoaded", function() {
            const tasaCambioInput = document.getElementById("tasa-cambio");

            tasaCambioInput.addEventListener("focus", function() {
                if (!tasaCambioInput.value) {
                    tasaCambioInput.value = 6.96; // valor por defecto
                }
            });
        });
        document.addEventListener("DOMContentLoaded", function() {
            const tipoSelect = document.getElementById("tipo");
            const labelDestinatario = document.getElementById("label-destinatario");

            function actualizarLabel() {
                switch (tipoSelect.value) {
                    case "ingreso":
                        labelDestinatario.textContent = "Recibido de:";
                        break;
                    case "egreso":
                        labelDestinatario.textContent = "Pagado a:";
                        break;
                    case "traspaso":
                        labelDestinatario.textContent = "Transferido a:";
                        break;
                    case "ajuste":
                        labelDestinatario.textContent = "Responsable:";
                        break;
                    default:
                        labelDestinatario.textContent = "Destinatario:";
                }
            }

            // Ejecutar al cargar por si hay valor seleccionado
            actualizarLabel();

            // Escuchar cambios
            tipoSelect.addEventListener("change", actualizarLabel);
        });
        let rowCount =
            {{ old('detalles') ? count(old('detalles')) : ($editMode ? count($comprobante->detalles) : 1) }};

        function updateSubmitButtonState() {
            const submitBtn = document.getElementById('submit-button');
            submitBtn.disabled = rowCount < 2;
        }

        function addRow() {
            const tbody = document.getElementById('detalle-rows');
            const newRow = tbody.rows[0].cloneNode(true);
            const inputs = newRow.querySelectorAll('input, select');
            inputs.forEach(el => {
                if (el.tagName === 'INPUT') {
                    el.value = '';
                } else if (el.tagName === 'SELECT') {
                    el.selectedIndex = 0;
                }

                const name = el.getAttribute('name');
                if (name) {
                    const newName = name.replace(/\[\d+\]/, `[${rowCount}]`);
                    el.setAttribute('name', newName);
                }

                const dataIndex = el.getAttribute('data-index');
                if (dataIndex) {
                    el.setAttribute('data-index', rowCount);
                }
            });
            tbody.appendChild(newRow);
            calculateAccountNumber(newRow.querySelector('.cuenta-nombre-select'))
            rowCount++;
            updateSubmitButtonState();
        }

        function removeRow(button) {
            const row = button.closest('tr');
            if (document.getElementById('detalle-rows').rows.length > 1) {
                rowCount--;
                row.remove();
                updateSubmitButtonState();
            }
        }

        function actualizarConversiones() {
            const tasa = parseFloat(document.getElementById('tasa-cambio').value);
            if (!tasa || tasa <= 0) return;

            const filas = document.querySelectorAll('#detalle-rows tr');
            filas.forEach(fila => {
                const inputDebe = fila.querySelector('input[name*="[debe]"]');
                const inputHaber = fila.querySelector('input[name*="[haber]"]');
                const usDebe = fila.querySelector('.us-debe input');
                const usHaber = fila.querySelector('.us-haber input');

                const debe = parseFloat(inputDebe.value) || 0;
                const haber = parseFloat(inputHaber.value) || 0;

                usDebe.value = (debe / tasa).toFixed(2);
                usHaber.value = (haber / tasa).toFixed(2);
            });
        }

        // Dispara actualización cada vez que se cambien valores en Bs
        document.addEventListener('input', function(e) {
            if (e.target.name?.includes('[debe]') || e.target.name?.includes('[haber]')) {
                actualizarConversiones();
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            updateSubmitButtonState()
            actualizarConversiones()
            const table = document.querySelector('#table-detalles');

            if (table)
                table.addEventListener('change', function(event) {
                    calculateAccountNumber(event.target)
                });
        });

        const calculateAccountNumber = (target) => {
            if (target && target.classList.contains('cuenta-nombre-select')) {
                const selectedOption = target.options[target.selectedIndex];
                const codigo = selectedOption.getAttribute('data-codigo');
                const index = target.dataset.index;

                const inputCodigo = target.closest('tr').querySelector(
                    `input[name="detalles[${index}][codigo_cuenta]"]`
                );
                if (inputCodigo) {
                    inputCodigo.value = codigo;
                }
            }
        }
    </script>
@endsection
