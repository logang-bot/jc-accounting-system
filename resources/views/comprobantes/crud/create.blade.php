@extends('layouts.admin')

@section('content')
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
@endsection
