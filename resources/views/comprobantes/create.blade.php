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

            <div class="grid grid-cols-1 gap-4 mb-6">
                @if ($editMode)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Número de Comprobante</label>
                        <input type="text" name="numero" class="w-full border rounded px-3 py-2"
                            value="{{ old('numero', $comprobante->numero) }}" required>
                        <small class="text-gray-500 text-sm">Puedes editar este número si es necesario. Asegúrate de que sea
                            único.</small>
                    </div>
                @endif
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
                    <input type="date" name="fecha" class="w-full border rounded px-3 py-2"
                        value="{{ old('fecha', $editMode ? $comprobante->fecha : '') }}" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                    <select name="tipo" class="w-full border rounded px-3 py-2" required>
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
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                    <textarea name="descripcion" rows="2" class="w-full border rounded px-3 py-2">{{ old('descripcion', $editMode ? $comprobante->descripcion : '') }}</textarea>
                </div>
            </div>

            <div class="flex items-center mb-4">
                <label for="tasa-cambio" class="mr-2 font-medium">Tasa de Cambio (Bs/USD):</label>
                <input type="number" step="0.0001" min="0.0001" id="tasa-cambio" name="tasa_cambio"
                    class="border rounded px-3 py-1 w-32" oninput="actualizarConversiones()"
                    value="{{ old('tasa_cambio', $editMode ? $comprobante->tasa_cambio : '') }}" required>
            </div>

            <h3 class="text-lg font-semibold mb-2">Detalle del Comprobante</h3>

            <div class="overflow-x-auto mb-6">
                <table class="min-w-full border rounded text-sm text-left">
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
                        @if ($editMode)
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
                                            class="w-full border rounded px-2 py-1" value="{{ $detalle->descripcion }}">
                                    </td>
                                    <td class="px-3 py-2">
                                        <input type="number" step="0.01" name="detalles[{{ $i }}][debe]"
                                            class="w-full text-right border rounded px-2 py-1"
                                            value="{{ $detalle->debe }}">
                                    </td>
                                    <td class="px-3 py-2">
                                        <input type="number" step="0.01" name="detalles[{{ $i }}][haber]"
                                            class="w-full text-right border rounded px-2 py-1"
                                            value="{{ $detalle->haber }}">
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
                                                data-codigo="{{ $cuenta->codigo_cuenta }}">{{ $cuenta->nombre_cuenta }}
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

                <button type="button" onclick="addRow()"
                    class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Agregar Línea
                </button>
            </div>

            <div class="text-right">
                <button type="submit"
                    class="px-6 py-2 {{ $editMode ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }} text-white rounded">
                    {{ $editMode ? 'Actualizar Comprobante' : 'Guardar Comprobante' }}
                </button>
            </div>
        </form>
    </div>

    <script>
        let rowCount = {{ $editMode ? count($comprobante->detalles) : 1 }};

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
            });
            tbody.appendChild(newRow);
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
            document.querySelectorAll('.cuenta-nombre-select').forEach(select => {
                select.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const codigo = selectedOption.getAttribute('data-codigo');
                    const index = this.dataset.index;

                    const inputCodigo = this.closest('tr').querySelector(
                        `input[name="detalles[${index}][codigo_cuenta]"]`);
                    if (inputCodigo) {
                        inputCodigo.value = codigo;
                    }
                });
            });
        });
    </script>
@endsection
