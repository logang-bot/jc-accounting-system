<!-- Modal Adicionar Cuenta -->
<div class="modal fade" id="modalCrearCuenta" tabindex="-1" aria-labelledby="modalCrearCuentaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCrearCuentaLabel">Crear Cuenta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Formulario de crear cuenta -->
                <form id="crearCuentaForm" method="POST" action="{{ route('cuentas.store') }}">
                    @csrf

                    <!-- Tipo de Cuenta -->
                    <div class="mb-3">
                        <label class="form-label">Tipo de Cuenta</label>
                        <div class="d-flex justify-content-between align-items-center">
                            @foreach (['Activo', 'Pasivo', 'Patrimonio', 'Ingresos', 'Egresos'] as $tipo)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tipo_cuenta"
                                        id="{{ strtolower($tipo) }}" value="{{ $tipo }}" required>
                                    <label class="form-check-label"
                                        for="{{ strtolower($tipo) }}">{{ $tipo }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Nivel de Cuenta -->
                    <div class="mb-3">
                        <label class="form-label">Nivel de Cuenta</label>
                        <div class="d-flex justify-content-between align-items-center">
                            @foreach (['Grupo', 'Rubro', 'Título', 'Cta-Compuesta', 'Sub-Cuenta'] as $nivel)
                                <div class="form-check">
                                    <input class="form-check-input nivel" type="checkbox" name="nivel"
                                        value="{{ $nivel }}">
                                    <label class="form-check-label">{{ $nivel }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Código de Cuenta -->
                    <div class="mb-3">
                        <label for="codigoCuenta" class="form-label">Código de Cuenta</label>
                        <div class="d-flex">
                            <input id="codigoCuenta1" type="text" class="form-control text-center me-1"
                                maxlength="1" readonly style="width: 45px;">
                            <input id="codigoCuenta2" type="text" class="form-control text-center me-1"
                                maxlength="1" disabled style="width: 45px;">
                            <input id="codigoCuenta3" type="text" class="form-control text-center me-1"
                                maxlength="2" disabled style="width: 65px;">
                            <input id="codigoCuenta4" type="text" class="form-control text-center me-1"
                                maxlength="2" disabled style="width: 65px;">
                            <input id="codigoCuenta5" type="text" class="form-control text-center me-1"
                                maxlength="4" disabled style="width: 70px;">
                            <input id="codigoCuentaFinal" type="text" class="form-control text-center"
                                name="codigo_cuenta" required readonly style="width: 156px; font-weight: bold;">
                        </div>
                    </div>

                    <!-- Nombre de la Cuenta y checkbox movimiento -->
                    <div class="mb-3">
                        <label for="editNombreCuenta" class="form-label">Nombre de la Cuenta</label>
                        <div class="d-flex align-items-center">
                            <input id="editNombreCuenta" type="text" class="form-control me-2" name="nombre_cuenta"
                                required>

                            <!-- Checkbox para marcar como cuenta de movimiento -->
                            <div class="form-check ms-2">
                                <input type="hidden" name="es_movimiento" value="0">
                                <input class="form-check-input" type="checkbox" id="es_movimiento" name="es_movimiento"
                                    value="1">
                                <label class="form-check-label" for="es_movimiento">
                                    Cuenta de Movimiento
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="d-flex justify-content-center mt-3">
                        <button type="submit" id="crearButton" class="btn btn-primary me-2">Crear</button>
                        <button type="button" class="btn btn-secondary" onclick="confirmCancel()">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Campos de código
        const codigoCuenta1 = document.getElementById("codigoCuenta1");
        const codigoCuenta2 = document.getElementById("codigoCuenta2");
        const codigoCuenta3 = document.getElementById("codigoCuenta3");
        const codigoCuenta4 = document.getElementById("codigoCuenta4");
        const codigoCuenta5 = document.getElementById("codigoCuenta5");
        const codigoCuentaFinal = document.getElementById("codigoCuentaFinal");

        // Niveles
        const nivelGrupo = document.querySelector("input[name='nivel'][value='Grupo']");
        const nivelRubro = document.querySelector("input[name='nivel'][value='Rubro']");
        const nivelTitulo = document.querySelector("input[name='nivel'][value='Título']");
        const nivelCompuesta = document.querySelector("input[name='nivel'][value='Cta-Compuesta']");
        const nivelSubCuenta = document.querySelector("input[name='nivel'][value='Sub-Cuenta']");
        const nivelCheckboxes = [nivelGrupo, nivelRubro, nivelTitulo, nivelCompuesta, nivelSubCuenta];

        // Movimiento
        const checkMovimiento = document.getElementById("es_movimiento");

        // Tipo de cuenta
        const tipoCuentaRadios = document.querySelectorAll("input[name='tipo_cuenta']");
        const tipoCuentaValores = {
            "Activo": "1",
            "Pasivo": "2",
            "Patrimonio": "3",
            "Ingresos": "4",
            "Egresos": "5"
        };

        // Asignar ceros por defecto
        [codigoCuenta1, codigoCuenta2, codigoCuenta3, codigoCuenta4, codigoCuenta5].forEach((input, index) => {
            const longitudes = [1, 1, 2, 2, 4];
            input.value = "0".repeat(longitudes[index]);
            input.disabled = true;
        });

        // Actualizar código final
        function actualizarCodigoFinal() {
            codigoCuentaFinal.value =
                (codigoCuenta1.value || "") +
                (codigoCuenta2.value || "") +
                (codigoCuenta3.value || "") +
                (codigoCuenta4.value || "") +
                (codigoCuenta5.value || "");
        }

        // Permitir solo números
        function permitirSoloNumeros(event) {
            event.target.value = event.target.value.replace(/[^0-9]/g, "");
            actualizarCodigoFinal();
        }

        [codigoCuenta1, codigoCuenta2, codigoCuenta3, codigoCuenta4, codigoCuenta5].forEach(input => {
            input.addEventListener("input", permitirSoloNumeros);
        });

        // Activar/desactivar input según checkbox
        function toggleInput(checkbox, input, maxLength) {
            input.disabled = !checkbox.checked;
            if (!checkbox.checked) {
                input.value = "0".repeat(maxLength);
            }
            input.maxLength = maxLength;
            actualizarCodigoFinal();
        }

        // Tipo de cuenta
        tipoCuentaRadios.forEach(radio => {
            radio.addEventListener("change", function() {
                codigoCuenta1.value = tipoCuentaValores[this.value] || "0";
                if (nivelGrupo) {
                    nivelGrupo.checked = true;
                    toggleInput(nivelGrupo, codigoCuenta1, 1);
                }
                actualizarCodigoFinal();
            });
        });

        // Niveles
        nivelCheckboxes.forEach((checkbox, index) => {
            const input = document.getElementById("codigoCuenta" + (index + 1));
            const maxLength = [1, 1, 2, 2, 4][index];

            checkbox.addEventListener("change", () => {
                toggleInput(checkbox, input, maxLength);

                if (checkbox === nivelSubCuenta) {
                    if (checkbox.checked) {
                        checkMovimiento.checked = true;
                        checkMovimiento.disabled = true;
                    } else {
                        checkMovimiento.checked = false;
                        checkMovimiento.disabled = false;
                    }
                }
            });
        });

        // Modal comportamiento
        const modalCrearCuenta = document.getElementById("modalAdicionarCuenta");
        const cancelarButton = document.querySelector(".btn-secondary");

        function limpiarFormulario() {
            tipoCuentaRadios.forEach(radio => radio.checked = false);
            nivelCheckboxes.forEach(checkbox => checkbox.checked = false);

            [codigoCuenta1, codigoCuenta2, codigoCuenta3, codigoCuenta4, codigoCuenta5].forEach((input,
                index) => {
                const longitudes = [1, 1, 2, 2, 4];
                input.value = "0".repeat(longitudes[index]);
                input.disabled = true;
            });

            codigoCuentaFinal.value = "";
            checkMovimiento.checked = false;
            checkMovimiento.disabled = false;
        }

        modalCrearCuenta.addEventListener("hidden.bs.modal", limpiarFormulario);

        cancelarButton.addEventListener("click", function(event) {
            event.preventDefault();
            Swal.fire({
                title: "¿Cancelar la adición?",
                text: "Se perderán todos los datos ingresados.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sí, cancelar",
                cancelButtonText: "No, continuar",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    limpiarFormulario();
                    let modalInstance = bootstrap.Modal.getInstance(modalCrearCuenta);
                    modalInstance.hide();
                    Swal.fire("Cancelado", "Los datos han sido eliminados.", "success");
                }
            });
        });

        document.getElementById("crearCuentaForm").addEventListener("submit", function(event) {
            event.preventDefault();
            Swal.fire({
                title: "¿Crear cuenta?",
                text: "Se guardará la nueva cuenta en el sistema.",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Sí, crear",
                cancelButtonText: "No, cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                    Swal.fire("¡Cuenta Creada!", "La cuenta ha sido agregada exitosamente.",
                        "success");
                }
            });
        });

        actualizarCodigoFinal();
    });
</script>
