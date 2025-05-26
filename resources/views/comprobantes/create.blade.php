@extends('layouts.admin')

@section('content')
    <div class="max-w-5xl mx-auto p-6 bg-white shadow-md rounded-xl">
        <h2 class="text-2xl font-semibold mb-6">Crear Comprobante</h2>

        <form method="POST" action="{{ route('comprobantes.store') }}">
            @csrf

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
                    <input type="date" name="fecha" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                    <select name="tipo" class="w-full border rounded px-3 py-2" required>
                        <option value="">-- Seleccione --</option>
                        <option value="ingreso">Ingreso</option>
                        <option value="egreso">Egreso</option>
                        <option value="traspaso">Traspaso</option>
                        <option value="ajuste">Ajuste</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                    <textarea name="descripcion" rows="2" class="w-full border rounded px-3 py-2"></textarea>
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
                    </tbody>
                </table>

                <button type="button" onclick="addRow()"
                    class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Agregar Línea
                </button>
            </div>

            <div class="text-right">
                <button type="submit" id="submit-button"
                    class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed"
                    disabled>
                    Guardar Comprobante
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





{{-- @section('content')
<div class="bg-primary pt-10 pb-21"></div>
<div class="container-fluid mt-n22 px-6">
    <div class="card">
        <div class="card-header bg-white py-4">
            <h4 class="mb-0">Registrar Nuevo Comprobante</h4>
        </div>
        <div class="card-body">
            <!-- Formulario de comprobante -->
            <form action="{{ route('comprobantes.store') }}" method="POST">
                @csrf

                <!-- Detalles Generales -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="numero_comprobante" class="form-label">Número de Comprobante</label>
                        <input type="text" id="numero_comprobante" name="numero_comprobante" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label for="fecha" class="form-label">Fecha</label>
                        <input type="date" id="fecha" name="fecha" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="tipo_comprobante" class="form-label">Tipo de Comprobante</label>
                        <select id="tipo_comprobante" name="tipo_comprobante" class="form-control" required>
                            <option value="Ingreso">Ingreso</option>
                            <option value="Egreso">Egreso</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="glosa_general" class="form-label">Glosa General</label>
                    <textarea id="glosa_general" name="glosa_general" class="form-control" rows="2"></textarea>
                </div>

                <!-- Tabla de detalles con estilo Excel -->
                <div class="table-responsive">
                    <table id="tabla-detalles" class="table table-bordered text-center" style="border-collapse: collapse;">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>Código</th>
                                <th>Cuenta</th>
                                <th>Debe</th>
                                <th>Haber</th>
                                <th>Glosa</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Primera fila para detalles -->
                            <tr>
                                <td contenteditable="true" class="excel-cell">12345</td>
                                <td contenteditable="true" class="excel-cell">Nombre de la Cuenta</td>
                                <td contenteditable="true" class="excel-cell">0.00</td>
                                <td contenteditable="true" class="excel-cell">0.00</td>
                                <td contenteditable="true" class="excel-cell">Descripción o comentario</td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm eliminar-fila">Eliminar</button>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <!-- Botón para agregar nuevas filas -->
                            <tr>
                                <td colspan="6">
                                    <button type="button" class="btn btn-success btn-sm agregar-fila">
                                        Agregar Detalle
                                    </button>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Botones -->
                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary">Guardar Comprobante</button>
                    <a href="{{ route('comprobantes.index') }}" class="btn btn-secondary mx-2">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script para manejar la tabla estilo Excel -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const tablaDetalles = document.querySelector('#tabla-detalles tbody');
        const agregarFilaBtn = document.querySelector('.agregar-fila');

        // Agregar nuevas filas dinámicamente
        agregarFilaBtn.addEventListener('click', () => {
            const nuevaFila = document.createElement('tr');
            nuevaFila.innerHTML = `
                <td contenteditable="true" class="excel-cell"></td>
                <td contenteditable="true" class="excel-cell"></td>
                <td contenteditable="true" class="excel-cell">0.00</td>
                <td contenteditable="true" class="excel-cell">0.00</td>
                <td contenteditable="true" class="excel-cell"></td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm eliminar-fila">Eliminar</button>
                </td>
            `;
            tablaDetalles.appendChild(nuevaFila);
        });

        // Eliminar filas
        tablaDetalles.addEventListener('click', (e) => {
            if (e.target.classList.contains('eliminar-fila')) {
                const fila = e.target.closest('tr');
                fila.remove();
            }
        });
    });
</script>
@endsection --}}
