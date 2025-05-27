@extends('layouts.admin')

@section('content')
    <div class="max-w-2xl mx-auto p-6 m-6 bg-white shadow-md rounded-xl">
        <h2 class="text-2xl font-semibold mb-6">
            {{ $editMode ? 'Editar Comprobante' : 'Crear Comprobante' }}
        </h2>

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

            <h3 class="text-lg font-semibold mb-2">Detalle del Comprobante</h3>

            <div class="overflow-x-auto mb-6">
                <table class="min-w-full border rounded text-sm text-left">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-3 py-2">Cuenta Contable</th>
                            <th class="px-3 py-2">Descripción</th>
                            <th class="px-3 py-2 text-right">Debe</th>
                            <th class="px-3 py-2 text-right">Haber</th>
                            <th class="px-3 py-2 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="detalle-rows">
                        @if ($editMode)
                            @foreach ($comprobante->detalles as $i => $detalle)
                                <tr>
                                    <td class="px-3 py-2">
                                        <select name="detalles[{{ $i }}][cuenta_id]"
                                            class="w-full border rounded px-2 py-1" required>
                                            @foreach ($cuentas as $cuenta)
                                                <option value="{{ $cuenta->id_cuenta }}"
                                                    {{ $detalle->cuenta_id == $cuenta->id_cuenta ? 'selected' : '' }}>
                                                    {{ $cuenta->codigo_cuenta }} - {{ $cuenta->nombre_cuenta }}
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
                                    <td class="px-3 py-2 text-center">
                                        <button type="button" onclick="removeRow(this)"
                                            class="text-red-600 hover:underline">Eliminar</button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="px-3 py-2">
                                    <select name="detalles[0][cuenta_id]" class="w-full border rounded px-2 py-1" required>
                                        @foreach ($cuentas as $cuenta)
                                            <option value="{{ $cuenta->id_cuenta }}">{{ $cuenta->codigo_cuenta }} -
                                                {{ $cuenta->nombre_cuenta }}</option>
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
        let rowCount = 1;

        function updateSubmitButtonState() {
            const submitBtn = document.getElementById('submit-button');
            submitBtn.disabled = rowCount < 2;
        }

        function addRow() {
            const tbody = document.getElementById('detalle-rows');
            const newRow = tbody.rows[0].cloneNode(true);
            const inputs = newRow.querySelectorAll('input, select');
            inputs.forEach(el => {
                const name = el.getAttribute('name');
                if (name) {
                    const newName = name.replace(/\[\d+\]/, `[${rowCount}]`);
                    el.setAttribute('name', newName);
                }
                if (el.tagName === 'INPUT') el.value = '';
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
    </script>
@endsection
