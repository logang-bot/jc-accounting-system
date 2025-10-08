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

            <div class="grid grid-cols-1 md:grid-cols-6 gap-4 mb-6">
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

                <!-- 🔥 NUEVO: Selección de moneda -->
                <div>
                    <label for="moneda" class="block text-sm font-medium text-gray-700 mb-1">Moneda:</label>
                    <select id="moneda" name="moneda" class="w-full border rounded px-3 py-2"
                        onchange="cambiarMoneda()">
                        <option value="BOB" selected>Bolivianos (Bs.)</option>
                        <option value="USD">Dólares (USD)</option>
                    </select>
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
                                @foreach ($oldDetalles as $i => $detalle)
                                    <tr>
                                        <td class="px-3 py-2">
                                            <input type="text" name="detalles[0][codigo_cuenta]"
                                                class="w-full bg-gray-100 border rounded px-2 py-1 text-sm" readonly
                                                value="{{ old("detalles.$i.cuenta.codigo_cuenta", $detalle->cuenta->codigo_cuenta ?? '') }}">
                                            <input type="hidden" name="detalles[0][cuenta_id]" class="cuenta-id-input"
                                                value="">
                                        </td>

                                        <td class="px-3 py-2">
                                            <input type="text" name="detalles[0][nombre_cuenta]"
                                                class="w-full border rounded px-2 py-1" readonly
                                                value="{{ old("detalles.$i.cuenta.nombre_cuenta", $detalle->cuenta->nombre_cuenta ?? '') }}">
                                        </td>

                                        <td class="px-3 py-2">
                                            <input type="text" name="detalles[{{ $i }}][descripcion]"
                                                class="w-full border rounded px-2 py-1"
                                                value="{{ $detalle['descripcion'] ?? '' }}">
                                        </td>

                                        <td class="px-3 py-2">
                                            <input type="number" step="0.01" name="detalles[{{ $i }}][debe]"
                                                class="w-full text-right border rounded px-2 py-1"
                                                value="{{ $detalle['debe'] ?? '' }}">
                                        </td>

                                        <td class="px-3 py-2">
                                            <input type="number" step="0.01"
                                                name="detalles[{{ $i }}][haber]"
                                                class="w-full text-right border rounded px-2 py-1"
                                                value="{{ $detalle['haber'] ?? '' }}">
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
                                        </td>

                                        <td class="px-3 py-2 text-center space-x-2">
                                            <!-- Botón seleccionar cuenta -->
                                            <button type="button" class="text-blue-600 select-cuenta-action"
                                                data-index="0">
                                                Seleccionar
                                            </button>

                                            <!-- Botón eliminar -->
                                            <button type="button" onclick="removeRow(this)"
                                                class="text-red-600 hover:underline">
                                                Eliminar
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @elseif ($editMode)
                                @foreach ($comprobante->detalles as $i => $detalle)
                                    <tr>
                                        <td class="px-3 py-2">
                                            <input type="text" name="detalles[0][codigo_cuenta]"
                                                class="w-full bg-gray-100 border rounded px-2 py-1 text-sm" readonly
                                                value="{{ old("detalles.$i.cuenta.codigo_cuenta", $detalle->cuenta->codigo_cuenta ?? '') }}">
                                            <input type="hidden" name="detalles[0][cuenta_id]" class="cuenta-id-input"
                                                value="">
                                        </td>

                                        <!-- Columna: Nombre de cuenta (select) -->
                                        <td class="px-3 py-2">
                                            <input type="text" name="detalles[0][nombre_cuenta]"
                                                class="w-full border rounded px-2 py-1" readonly
                                                value="{{ old("detalles.$i.cuenta.nombre_cuenta", $detalle->cuenta->nombre_cuenta ?? '') }}">
                                        </td>
                                        <td class="px-3 py-2">
                                            <input type="text" name="detalles[{{ $i }}][descripcion]"
                                                class="w-full border rounded px-2 py-1"
                                                value="{{ old("detalles.$i.descripcion", $detalle->descripcion ?? '') }}">
                                        </td>
                                        <td class="px-3 py-2">
                                            <div>
                                                <input type="number" step="0.01"
                                                    name="detalles[{{ $i }}][debe]"
                                                    class="w-full text-right border rounded px-2 py-1"
                                                    value="{{ old("detalles.$i.debe", $detalle->debe ?? 0) }}">
                                            </div>
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
                                                value="{{ number_format($detalle->debe / ($comprobante->tasa_cambio ?: 6.96), 2) }}">
                                        </td>
                                        <td class="px-3 py-2 text-right us-haber">
                                            <input type="text" readonly
                                                class="w-full text-right bg-gray-100 border rounded px-2 py-1"
                                                value="{{ number_format($detalle->haber / ($comprobante->tasa_cambio ?: 6.96), 2) }}">
                                        </td>


                                        <td class="px-3 py-2 text-right iva">
                                            <input type="number" step="0.01" min="0" max="100"
                                                name="detalles[{{ $i }}][iva]"
                                                class="w-20 text-right border rounded px-2 py-1" placeholder="0.00">
                                        </td>

                                        <td class="px-3 py-2 text-center space-x-2">
                                            <!-- Botón seleccionar cuenta -->
                                            <button type="button" class="text-blue-600 select-cuenta-action"
                                                data-index="0">
                                                Seleccionar
                                            </button>
                                            <!-- Botón eliminar -->
                                            <button type="button" onclick="removeRow(this)"
                                                class="text-red-600 hover:underline">
                                                Eliminar
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="px-3 py-2">
                                        <input type="text" name="detalles[0][codigo_cuenta]"
                                            class="w-full bg-gray-100 border rounded px-2 py-1 text-sm" readonly>
                                        <input type="hidden" name="detalles[0][cuenta_id]" class="cuenta-id-input"
                                            value="">
                                    </td>

                                    <td class="px-3 py-2">
                                        <input type="text" name="detalles[0][nombre_cuenta]"
                                            class="w-full border rounded px-2 py-1" readonly>
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
                                    <td class="px-3 py-2 text-center space-x-2">
                                        <!-- Botón seleccionar cuenta -->
                                        <button type="button" class="text-blue-600 select-cuenta-action" data-index="0">
                                            Seleccionar
                                        </button>
                                        <!-- Botón eliminar -->
                                        <button type="button" onclick="removeRow(this)"
                                            class="text-red-600 hover:underline">
                                            Eliminar
                                        </button>
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
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white w-full max-w-[90%] rounded-lg shadow-lg">

                <!-- Modal Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b">
                    <h5 class="text-lg font-semibold">
                        {{ __('Plan de Cuentas') }}
                    </h5>
                    <button class="text-gray-500 hover:text-gray-700 text-xl font-bold"
                        data-hs-overlay="#show-plan-cuentas-modal">&times;</button>
                </div>

                <!-- Modal Body -->
                <div class="p-6">
                    @include('cuentas.partials.planCuentas')
                </div>
            </div>
        </div>
    </x-modal>

    <!-- Modal seleccionar cuenta -->
    <x-modal id="select-cuenta-modal" class="hidden">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white w-full max-w-4xl rounded-lg shadow-lg">

                <!-- Modal Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b bg-blue-600 rounded-t-lg">
                    <h5 class="text-lg font-semibold text-white">Seleccionar Cuenta Contable</h5>
                    <button class="text-white hover:text-gray-200 text-xl font-bold"
                        data-hs-overlay="#select-cuenta-modal">&times;</button>
                </div>

                <!-- Modal Body -->
                <div class="p-6">
                    <!-- Filtro -->
                    <div class="mb-4">
                        <input type="text" id="buscar-cuenta" placeholder="Buscar por código o nombre..."
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:ring-2 focus:outline-none" />
                    </div>

                    <!-- Tabla -->
                    <div class="overflow-y-auto max-h-96 border rounded">
                        <table class="w-full text-sm border-collapse">
                            <thead class="bg-gray-100 sticky top-0">
                                <tr>
                                    <th class="px-3 py-2 border">Código</th>
                                    <th class="px-3 py-2 border">Nombre</th>
                                    <th class="px-3 py-2 border text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody id="tabla-cuentas">
                                @foreach ($cuentas as $cuenta)
                                    <tr class="hover:bg-gray-100 cursor-pointer" data-id="{{ $cuenta->id_cuenta }}"
                                        data-nombre="{{ $cuenta->nombre_cuenta }}"
                                        data-codigo="{{ $cuenta->codigo_cuenta }}">
                                        <td class="px-3 py-2 border">{{ $cuenta->codigo_cuenta }}</td>
                                        <td class="px-3 py-2 border">{{ $cuenta->nombre_cuenta }}</td>
                                        <td class="px-3 py-2 border text-center">
                                            <button type="button"
                                                class="px-2 py-1 bg-blue-600 text-white rounded select-cuenta-btn"
                                                data-id="{{ $cuenta->id_cuenta }}"
                                                data-nombre="{{ $cuenta->nombre_cuenta }}"
                                                data-codigo="{{ $cuenta->codigo_cuenta }}">
                                                Seleccionar
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </x-modal>

    <script>
        let rowCount = {{ old('detalles') ? count(old('detalles')) : ($editMode ? count($comprobante->detalles) : 1) }};
        let filaActiva = null;

        document.addEventListener("DOMContentLoaded", function() {
            const tasaCambioInput = document.getElementById("tasa-cambio");

            // Valor por defecto de la tasa
            if (!tasaCambioInput.value) tasaCambioInput.value = 6.96;

            cambiarMoneda();
            actualizarLabelTipo();
            actualizarConversiones();
            updateSubmitButtonState();

            const closeModalBtn = document.querySelector("#select-cuenta-modal .text-xl.font-bold");
            if (closeModalBtn) {
                closeModalBtn.addEventListener("click", function() {
                    const modal = document.getElementById("select-cuenta-modal");
                    if (modal) modal.classList.add("hidden");
                    filaActiva = null;
                });
            }
        });

        // ────────── Conversión Bs/USD ──────────
        function cambiarMoneda() {
            const moneda = document.getElementById('moneda').value;
            const filas = document.querySelectorAll('#detalle-rows tr');
            const tasa = parseFloat(document.getElementById('tasa-cambio').value) || 6.96;

            filas.forEach(fila => {
                const debeBs = fila.querySelector('input[name*="[debe]"]');
                const haberBs = fila.querySelector('input[name*="[haber]"]');
                const debeUSD = fila.querySelector('.us-debe input');
                const haberUSD = fila.querySelector('.us-haber input');

                if (moneda === "USD") {
                    debeUSD.removeAttribute('readonly');
                    haberUSD.removeAttribute('readonly');
                    debeUSD.classList.remove('bg-gray-100');
                    haberUSD.classList.remove('bg-gray-100');
                    debeBs.setAttribute('readonly', true);
                    haberBs.setAttribute('readonly', true);
                    debeBs.classList.add('bg-gray-100');
                    haberBs.classList.add('bg-gray-100');

                    debeUSD.value = ((parseFloat(debeBs.value) || 0) / tasa).toFixed(2);
                    haberUSD.value = ((parseFloat(haberBs.value) || 0) / tasa).toFixed(2);
                } else {
                    debeBs.removeAttribute('readonly');
                    haberBs.removeAttribute('readonly');
                    debeBs.classList.remove('bg-gray-100');
                    haberBs.classList.remove('bg-gray-100');
                    debeUSD.setAttribute('readonly', true);
                    haberUSD.setAttribute('readonly', true);
                    debeUSD.classList.add('bg-gray-100');
                    haberUSD.classList.add('bg-gray-100');
                }
            });
        }

        // Actualizar Bs al editar USD
        document.addEventListener('input', function(e) {
            const moneda = document.getElementById('moneda').value;
            if (moneda === "USD" && (e.target.closest('.us-debe') || e.target.closest('.us-haber'))) {
                const fila = e.target.closest('tr');
                const tasa = parseFloat(document.getElementById('tasa-cambio').value) || 6.96;
                fila.querySelector('input[name*="[debe]"]').value = ((parseFloat(fila.querySelector(
                    '.us-debe input').value) || 0) * tasa).toFixed(2);
                fila.querySelector('input[name*="[haber]"]').value = ((parseFloat(fila.querySelector(
                    '.us-haber input').value) || 0) * tasa).toFixed(2);
            }
        });

        // ────────── Actualizar label según tipo ──────────
        function actualizarLabelTipo() {
            const tipoSelect = document.getElementById("tipo");
            const labelDestinatario = document.getElementById("label-destinatario");

            function actualizar() {
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

            actualizar();
            tipoSelect.addEventListener("change", actualizar);
        }

        // ────────── Agregar / Eliminar filas ──────────
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
                    if (el.name?.includes('[debe]') || el.name?.includes('[haber]') || el.closest('.us-debe') || el
                        .closest('.us-haber')) {
                        el.value = "0.00";
                    } else if (el.name?.includes('[cuenta_id]') || el.name?.includes('[codigo_cuenta]') || el.name
                        ?.includes('[nombre_cuenta]')) {
                        el.value = '';
                    } else {
                        el.value = '';
                    }
                }
                if (el.tagName === 'SELECT') el.selectedIndex = 0;

                const name = el.getAttribute('name');
                if (name) el.setAttribute('name', name.replace(/\[\d+\]/, `[${rowCount}]`));

                const dataIndex = el.getAttribute('data-index');
                if (dataIndex) el.setAttribute('data-index', rowCount);
            });

            tbody.appendChild(newRow);
            rowCount++;
            updateSubmitButtonState();
        }

        function removeRow(btn) {
            const row = btn.closest('tr');
            if (document.getElementById('detalle-rows').rows.length > 1) {
                row.remove();
                rowCount--;
                updateSubmitButtonState();
            }
        }

        // ────────── Conversion Bs/USD de todas las filas ──────────
        function actualizarConversiones() {
            const tasa = parseFloat(document.getElementById('tasa-cambio').value);
            if (!tasa || tasa <= 0) return;
            document.querySelectorAll('#detalle-rows tr').forEach(fila => {
                const debe = parseFloat(fila.querySelector('input[name*="[debe]"]').value) || 0;
                const haber = parseFloat(fila.querySelector('input[name*="[haber]"]').value) || 0;
                fila.querySelector('.us-debe input').value = (debe / tasa).toFixed(2);
                fila.querySelector('.us-haber input').value = (haber / tasa).toFixed(2);
            });
        }

        document.addEventListener('input', function(e) {
            if (e.target.name?.includes('[debe]') || e.target.name?.includes('[haber]')) actualizarConversiones();
        });

        // ────────── Select cuentas dentro de fila ──────────
        function calculateAccountNumber(select) {
            if (select && select.classList.contains('cuenta-nombre-select')) {
                const option = select.options[select.selectedIndex];
                const codigo = option.getAttribute('data-codigo');
                select.closest('tr').querySelector(`input[name*="[codigo_cuenta]"]`).value = codigo;
            }
        }

        // ────────── Abrir modal de selección de cuenta (delegación de eventos) ──────────
        document.addEventListener("click", function(e) {
            if (e.target.classList.contains("select-cuenta-action")) {
                filaActiva = e.target.closest("tr");
                document.querySelector("#select-cuenta-modal").classList.remove("hidden");
                document.getElementById("buscar-cuenta").focus();
            }
        });

        // ────────── Filtrar cuentas en modal ──────────
        document.getElementById("buscar-cuenta").addEventListener("input", function() {
            const filtro = this.value.toLowerCase();
            document.querySelectorAll("#tabla-cuentas tr").forEach(fila => {
                const codigo = fila.dataset.codigo.toLowerCase();
                const nombre = fila.dataset.nombre.toLowerCase();
                fila.style.display = (codigo.includes(filtro) || nombre.includes(filtro)) ? "" : "none";
            });
        });

        // ────────── Seleccionar cuenta desde modal ──────────
        document.querySelectorAll(".select-cuenta-btn").forEach(btn => {
            btn.addEventListener("click", function() {
                if (filaActiva !== null) {
                    const id = this.dataset.id;
                    const codigo = this.dataset.codigo;
                    const nombre = this.dataset.nombre;

                    const inputNombre = filaActiva.querySelector(`input[name*="[nombre_cuenta]"]`);
                    const inputCodigo = filaActiva.querySelector(`input[name*="[codigo_cuenta]"]`);
                    const inputCuentaId = filaActiva.querySelector(`input[name*="[cuenta_id]"]`);

                    if (inputNombre) inputNombre.value = nombre;
                    if (inputCodigo) inputCodigo.value = codigo;
                    if (inputCuentaId) inputCuentaId.value = id;

                    document.querySelector('#select-cuenta-modal').classList.add("hidden");
                    filaActiva = null;
                }
            });
        });
    </script>



@endsection
