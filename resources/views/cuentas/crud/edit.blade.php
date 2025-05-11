<!-- Modal Editar Cuenta -->
<div class="modal fade" id="modalEditarCuenta" tabindex="-1" aria-labelledby="modalEditarCuentaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarCuentaLabel">Editar Cuenta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarCuenta" method="POST" action="">
                    @csrf
                    @method('PUT')

                    <!-- Tipo de Cuenta -->
                    <div class="mb-3">
                        <label class="form-label">Tipo de Cuenta</label>
                        <div class="d-flex justify-content-between align-items-center">
                            @foreach (['Activo', 'Pasivo', 'Patrimonio', 'Ingresos', 'Egresos'] as $tipo)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tipo_cuenta"
                                        id="editTipo{{ strtolower($tipo) }}" value="{{ $tipo }}">
                                    <label class="form-check-label"
                                        for="editTipo{{ strtolower($tipo) }}">{{ $tipo }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Nivel de Cuenta -->
                    <div class="mb-3">
                        <label class="form-label">Nivel de Cuenta</label>
                        <div class="d-flex justify-content-between align-items-center">
                            @foreach (['1' => 'Grupo', '2' => 'Rubro', '3' => 'Título', '4' => 'Cta-Compuesta', '5' => 'Sub-Cuenta'] as $value => $nivel)
                                <div class="form-check">
                                    <input class="form-check-input nivel" type="checkbox" name="nivel[]"
                                        id="editNivel{{ $value }}" value="{{ $value }}">
                                    <label class="form-check-label"
                                        for="editNivel{{ $value }}">{{ $nivel }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Código de Cuenta -->
                    <div class="mb-3">
                        <label for="editCodigoCuentaFinal" class="form-label">Código de Cuenta</label>
                        <div class="d-flex">
                            <input id="editCodigoCuenta1" type="text" class="form-control text-center me-1"
                                maxlength="1" readonly style="width: 45px;">
                            <input id="editCodigoCuenta2" type="text" class="form-control text-center me-1"
                                maxlength="1" disabled style="width: 45px;">
                            <input id="editCodigoCuenta3" type="text" class="form-control text-center me-1"
                                maxlength="2" disabled style="width: 65px;">
                            <input id="editCodigoCuenta4" type="text" class="form-control text-center me-1"
                                maxlength="2" disabled style="width: 65px;">
                            <input id="editCodigoCuenta5" type="text" class="form-control text-center me-1"
                                maxlength="4" disabled style="width: 70px;">
                            <input id="editCodigoCuentaFinal" type="text" class="form-control text-center"
                                name="codigo_cuenta" required readonly style="width: 156px; font-weight: bold;">
                        </div>
                    </div>

                    <!-- Nombre de la Cuenta y checkbox movimiento -->
                    <div class="mb-3">
                        <label for="editNombreCuenta" class="form-label">Nombre de la Cuenta</label>
                        <div class="d-flex align-items-center">
                            <input id="editNombreCuenta" type="text" class="form-control me-2" name="nombre_cuenta"
                                required>

                            <div class="form-check ms-2">
                                <input type="hidden" name="es_movimiento" value="0">
                                <input class="form-check-input" type="checkbox" id="editEsMovimiento"
                                    name="es_movimiento" value="1">
                                <label class="form-check-label" for="editEsMovimiento">Cuenta de
                                    Movimiento</label>
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="d-flex justify-content-center mt-3">
                        <button type="submit" id="editarButton" class="btn btn-primary me-2">Guardar
                            Cambios</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const modalEditarCuenta = new bootstrap.Modal(document.getElementById("modalEditarCuenta"));

        // Función para abrir el modal de edición y cargar datos
        function abrirModalEditar(id) {
            fetch(`/cuentas/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        Swal.fire('Error', data.error, 'error');
                        return;
                    }

                    document.getElementById("formEditarCuenta").action = `/cuentas/${data.id_cuenta}`;
                    document.getElementById("editNombreCuenta").value = data.nombre_cuenta;
                    document.getElementById("editCodigoCuentaFinal").value = data.codigo_cuenta;

                    const tipoCuentaInput = document.querySelector(
                        `input[name="tipo_cuenta"][value="${data.tipo_cuenta}"]`
                    );
                    if (tipoCuentaInput) tipoCuentaInput.checked = true;

                    const nivelesSeleccionados = Array.isArray(data.nivel) ? data.nivel.map(String) : [
                        String(data.nivel)
                    ];
                    document.querySelectorAll("input[name='nivel[]']").forEach(checkbox => {
                        checkbox.checked = nivelesSeleccionados.includes(checkbox.value);
                    });

                    const checkMovimiento = document.getElementById("editEsMovimiento");
                    checkMovimiento.checked = data.es_movimiento === 1;

                    // Separar el código de cuenta en partes si deseas (opcional)
                    const codigo = data.codigo_cuenta || '';
                    document.getElementById("editCodigoCuenta1").value = codigo.substring(0, 1) || '';
                    document.getElementById("editCodigoCuenta2").value = codigo.substring(1, 2) || '';
                    document.getElementById("editCodigoCuenta3").value = codigo.substring(2, 4) || '';
                    document.getElementById("editCodigoCuenta4").value = codigo.substring(4, 6) || '';
                    document.getElementById("editCodigoCuenta5").value = codigo.substring(6, 10) || '';

                    modalEditarCuenta.show();
                })
                .catch(error => {
                    console.error('Error real:', error);
                    Swal.fire('Error', 'No se pudieron cargar los datos de la cuenta.', 'error');
                });
        }

        // Solo un evento para botones .btn-editar
        document.querySelectorAll(".btn-editar").forEach(button => {
            button.addEventListener("click", function() {
                const cuentaId = this.dataset.id;
                if (cuentaId) {
                    abrirModalEditar(cuentaId);
                } else {
                    Swal.fire('Error', 'ID de cuenta no válido', 'error');
                }
            });
        });
    });
</script>
