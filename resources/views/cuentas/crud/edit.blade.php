<!-- Modal Editar Cuenta -->
<div class="flex items-center justify-center min-h-screen px-4">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-xl"">
        <div class="bg-white rounded-lg shadow-lg">
            <div class="flex items-center justify-between p-4 border-b">
                <h5 class="text-lg font-semibold">Editar Cuenta</h5>
                <button type="button" class="text-gray-500 hover:text-gray-700" aria-controls="cuentas-edit"
                    data-hs-overlay="#cuentas-edit">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="p-4">
                <form id="formEditarCuenta" method="POST" action="">
                    @csrf
                    @method('PUT')

                    <!-- Tipo de Cuenta -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Cuenta</label>
                        <div class="flex flex-wrap gap-4">
                            @foreach (['Activo', 'Pasivo', 'Patrimonio', 'Ingresos', 'Egresos'] as $tipo)
                                <div class="flex items-center space-x-2">
                                    <input class="text-blue-600 border-gray-300 focus:ring-blue-500" type="radio"
                                        name="tipo_cuenta" id="editTipo{{ strtolower($tipo) }}">
                                    <label for="editTipo{{ strtolower($tipo) }}"
                                        class="text-sm text-gray-700">{{ $tipo }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Nivel de Cuenta -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nivel de Cuenta</label>
                        <div class="flex flex-wrap gap-4">
                            @foreach (['1' => 'Grupo', '2' => 'Rubro', '3' => 'Título', '4' => 'Cta-Compuesta', '5' => 'Sub-Cuenta'] as $value => $nivel)
                                <div class="flex items-center space-x-2">
                                    <input class="text-blue-600 border-gray-300 focus:ring-blue-500 nivel"
                                        type="checkbox" name="nivel[]" id="editNivel{{ $value }}"
                                        value="{{ $value }}">
                                    <label for="editNivel{{ $value }}"
                                        class="text-sm text-gray-700">{{ $nivel }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Código de Cuenta -->
                    <div class="mb-4">
                        <label for="editCodigoCuentaFinal" class="block text-sm font-medium text-gray-700 mb-2">Código
                            de Cuenta</label>
                        <div class="flex flex-wrap gap-1">
                            <input id="editCodigoCuenta1" type="text" maxlength="1" readonly
                                class="w-11 text-center border rounded px-2 py-1 text-sm bg-gray-100" />
                            <input id="editCodigoCuenta2" type="text" maxlength="1" disabled
                                class="w-11 text-center border rounded px-2 py-1 text-sm bg-gray-100" />
                            <input id="editCodigoCuenta3" type="text" maxlength="2" disabled
                                class="w-16 text-center border rounded px-2 py-1 text-sm bg-gray-100" />
                            <input id="editCodigoCuenta4" type="text" maxlength="2" disabled
                                class="w-16 text-center border rounded px-2 py-1 text-sm bg-gray-100" />
                            <input id="editCodigoCuenta5" type="text" maxlength="4" disabled
                                class="w-20 text-center border rounded px-2 py-1 text-sm bg-gray-100" />
                            <input id="codigoCuentaEditar" type="text" name="codigo_cuenta" required readonly
                                class="w-40 text-center font-bold border rounded px-2 py-1 text-sm bg-gray-100" />
                        </div>
                    </div>

                    <!-- Nombre de la Cuenta y checkbox movimiento -->
                    <div class="mb-4">
                        <label for="editNombreCuenta" class="block text-sm font-medium text-gray-700 mb-2">Nombre de la
                            Cuenta</label>
                        <div class="flex items-center gap-4">
                            <input id="nombreCuentaEditar" type="text" name="nombre_cuenta" required
                                class="flex-1 border rounded px-3 py-2 text-sm" />

                            <div class="flex items-center space-x-2">
                                <input type="hidden" name="es_movimiento" value="0">
                                <input class="text-blue-600 border-gray-300 focus:ring-blue-500" type="checkbox"
                                    id="editEsMovimiento" name="es_movimiento" value="1">
                                <label for="editEsMovimiento" class="text-sm text-gray-700">Cuenta de Movimiento</label>
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-center mt-6 gap-4">
                        <button type="submit" id="editarButton"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow text-sm">Guardar
                            Cambios</button>
                        <button type="button" aria-controls="cuentas-edit" data-hs-overlay="#cuentas-edit"
                            class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded shadow text-sm">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // const modalEditarCuenta = new bootstrap.Modal(document.getElementById("modalEditarCuenta"));

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
