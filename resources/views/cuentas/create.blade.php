<!-- Modal Adicionar Cuenta -->
{{-- <div class="flex items-center justify-center min-h-screen px-4">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-xl">
        <div class="flex justify-between items-center p-4 border-b">
            <h5 class="text-lg font-semibold">Crear Cuenta</h5>
            <button type="button" aria-controls="cuentas-create" data-hs-overlay="#cuentas-create"
                class="text-gray-500 hover:text-black">
                &times;
            </button>
        </div>
        <div class="p-4">
            <form id="crearCuentaForm" method="POST" action="{{ route('cuentas.store') }}">
                @csrf

                <!-- Tipo de Cuenta -->
                <div class="mb-4">
                    <label class="block font-medium mb-2">Tipo de Cuenta</label>
                    <div class="flex justify-between flex-wrap gap-2">
                        @foreach (['Activo', 'Pasivo', 'Patrimonio', 'Ingresos', 'Egresos'] as $tipo)
                            <div class="flex items-center gap-1">
                                <input type="radio" name="tipo_cuenta" id="{{ strtolower($tipo) }}"
                                    value="{{ $tipo }}" required class="accent-blue-600">
                                <label for="{{ strtolower($tipo) }}">{{ $tipo }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Nivel de Cuenta -->
                <div class="mb-4">
                    <label class="block font-medium mb-2">Nivel de Cuenta</label>
                    <div class="flex justify-between flex-wrap gap-2">
                        @foreach (['Grupo', 'Rubro', 'Título', 'Cta-Compuesta', 'Sub-Cuenta'] as $nivel)
                            <div class="flex items-center gap-1">
                                <input type="checkbox" name="nivel" value="{{ $nivel }}"
                                    class="accent-blue-600">
                                <label>{{ $nivel }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Código de Cuenta -->
                <div class="mb-4">
                    <label for="codigoCuenta" class="block font-medium mb-2">Código de Cuenta</label>
                    <div class="flex gap-1">
                        <input id="codigoCuenta1" type="text" maxlength="1" readonly
                            class="w-11 text-center border rounded px-2 py-1">
                        <input id="codigoCuenta2" type="text" maxlength="1" disabled
                            class="w-11 text-center border rounded px-2 py-1">
                        <input id="codigoCuenta3" type="text" maxlength="2" disabled
                            class="w-16 text-center border rounded px-2 py-1">
                        <input id="codigoCuenta4" type="text" maxlength="2" disabled
                            class="w-16 text-center border rounded px-2 py-1">
                        <input id="codigoCuenta5" type="text" maxlength="4" disabled
                            class="w-20 text-center border rounded px-2 py-1">
                        <input id="codigoCuentaFinal" type="text" name="codigo_cuenta" required readonly
                            class="w-40 font-bold text-center border rounded px-2 py-1">
                    </div>
                </div>

                <!-- Nombre de la Cuenta y checkbox movimiento -->
                <div class="mb-4">
                    <label for="editNombreCuenta" class="block font-medium mb-2">Nombre de la Cuenta</label>
                    <div class="flex items-center gap-4">
                        <input id="editNombreCuenta" type="text" name="nombre_cuenta" required
                            class="flex-1 border rounded px-3 py-2">
                        <div class="flex items-center gap-1">
                            <input type="hidden" name="es_movimiento" value="0">
                            <input type="checkbox" id="es_movimiento" name="es_movimiento" value="1"
                                class="accent-blue-600">
                            <label for="es_movimiento">Cuenta de Movimiento</label>
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex justify-center mt-6 gap-4">
                    <button type="submit" id="crearButton"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Crear</button>
                    <button type="button" aria-controls="cuentas-create" data-hs-overlay="#cuentas-create"
                        class="bg-gray-300 text-black px-4 py-2 rounded hover:bg-gray-400">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div> --}}
@extends('layouts.admin')

@section('content')
    <div class="max-w-2xl mx-auto p-6 m-6 bg-white rounded-xl shadow">
        <h2 class="text-2xl font-bold mb-4">{{ $modo === 'editar' ? 'Editar cuenta' : 'Crear Cuenta Contable' }}</h2>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ $modo === 'editar' ? route('cuentas.update', $cuenta->id_cuenta) : route('cuentas.store') }}"
            method="POST" class="space-y-4">
            @csrf
            @if ($modo === 'editar')
                @method('PUT')
            @endif

            {{-- Nombre de la cuenta --}}
            <div>
                <label for="nombre_cuenta" class="block text-sm font-medium text-gray-700">Nombre de la Cuenta</label>
                <input type="text" name="nombre_cuenta" id="nombre_cuenta"
                    value="{{ old('nombre_cuenta', $cuenta->nombre_cuenta ?? '') }}"
                    class="mt-1 block w-full border border-gray-300 rounded px-3 py-2" required>
            </div>

            {{-- Tipo de cuenta --}}
            <div>
                <label for="tipo_cuenta" class="block text-sm font-medium text-gray-700">Tipo de Cuenta</label>
                <select name="tipo_cuenta" id="tipo_cuenta" {{ isset($cuenta) && $cuenta->hasChildren() ? 'disabled' : '' }}
                    class="mt-1 block w-full border border-gray-300 rounded px-3 py-2" required>
                    <option value="">-- Seleccione --</option>
                    @foreach (['Activo', 'Pasivo', 'Patrimonio', 'Ingresos', 'Egresos'] as $tipo)
                        <option value="{{ $tipo }}"
                            {{ old('tipo_cuenta', $cuenta->tipo_cuenta ?? '') == $tipo ? 'selected' : '' }}>
                            {{ $tipo }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Cuenta padre --}}
            <div>
                <label for="parent_id" class="block text-sm font-medium text-gray-700">Cuenta Padre</label>
                <select name="parent_id" id="parent_id" class="mt-1 block w-full border border-gray-300 rounded px-3 py-2"
                    {{ isset($cuenta) && $cuenta->hasChildren() ? 'disabled' : '' }}>
                    <option value="">-- Ninguna (Cuenta Raíz) --</option>
                    @foreach ($cuentasPadre as $padre)
                        <option value="{{ $padre->id_cuenta }}" data-tipo="{{ $padre->tipo_cuenta }}"
                            {{ old('parent_id', $cuenta->parent_id ?? '') == $padre->id_cuenta ? 'selected' : '' }}>
                            {{ $padre->nombre_cuenta }} ({{ $padre->codigo_cuenta }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Es movimiento --}}
            <div class="flex items-center gap-3">
                <input type="checkbox" name="es_movimiento" value="1"
                    {{ old('es_movimiento', $cuenta->es_movimiento ?? false) ? 'checked' : '' }}
                    {{ isset($cuenta) && $cuenta->hasChildren() ? 'disabled' : '' }}>
                <label for="es_movimiento" class="text-sm font-medium text-gray-700">Es cuenta de movimiento</label>
            </div>

            <div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded">
                    {{ $modo === 'editar' ? 'Actualizar cuenta' : 'Crear cuenta' }}
                </button>
            </div>
        </form>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // document.addEventListener("DOMContentLoaded", function() {
        //     // Campos de código
        //     const codigoCuenta1 = document.getElementById("codigoCuenta1");
        //     const codigoCuenta2 = document.getElementById("codigoCuenta2");
        //     const codigoCuenta3 = document.getElementById("codigoCuenta3");
        //     const codigoCuenta4 = document.getElementById("codigoCuenta4");
        //     const codigoCuenta5 = document.getElementById("codigoCuenta5");
        //     const codigoCuentaFinal = document.getElementById("codigoCuentaFinal");

        //     // Niveles
        //     const nivelGrupo = document.querySelector("input[name='nivel'][value='Grupo']");
        //     const nivelRubro = document.querySelector("input[name='nivel'][value='Rubro']");
        //     const nivelTitulo = document.querySelector("input[name='nivel'][value='Título']");
        //     const nivelCompuesta = document.querySelector("input[name='nivel'][value='Cta-Compuesta']");
        //     const nivelSubCuenta = document.querySelector("input[name='nivel'][value='Sub-Cuenta']");
        //     const nivelCheckboxes = [nivelGrupo, nivelRubro, nivelTitulo, nivelCompuesta, nivelSubCuenta];

        //     // Movimiento
        //     const checkMovimiento = document.getElementById("es_movimiento");

        //     // Tipo de cuenta
        //     const tipoCuentaRadios = document.querySelectorAll("input[name='tipo_cuenta']");
        //     const tipoCuentaValores = {
        //         "Activo": "1",
        //         "Pasivo": "2",
        //         "Patrimonio": "3",
        //         "Ingresos": "4",
        //         "Egresos": "5"
        //     };

        //     // Asignar ceros por defecto
        //     [codigoCuenta1, codigoCuenta2, codigoCuenta3, codigoCuenta4, codigoCuenta5].forEach((input, index) => {
        //         const longitudes = [1, 1, 2, 2, 4];
        //         input.value = "0".repeat(longitudes[index]);
        //         input.disabled = true;
        //     });

        //     // Actualizar código final
        //     function actualizarCodigoFinal() {
        //         codigoCuentaFinal.value =
        //             (codigoCuenta1.value || "") +
        //             (codigoCuenta2.value || "") +
        //             (codigoCuenta3.value || "") +
        //             (codigoCuenta4.value || "") +
        //             (codigoCuenta5.value || "");
        //     }

        //     // Permitir solo números
        //     function permitirSoloNumeros(event) {
        //         event.target.value = event.target.value.replace(/[^0-9]/g, "");
        //         actualizarCodigoFinal();
        //     }

        //     [codigoCuenta1, codigoCuenta2, codigoCuenta3, codigoCuenta4, codigoCuenta5].forEach(input => {
        //         input.addEventListener("input", permitirSoloNumeros);
        //     });

        //     // Activar/desactivar input según checkbox
        //     function toggleInput(checkbox, input, maxLength) {
        //         input.disabled = !checkbox.checked;
        //         if (!checkbox.checked) {
        //             input.value = "0".repeat(maxLength);
        //         }
        //         input.maxLength = maxLength;
        //         actualizarCodigoFinal();
        //     }

        //     // Tipo de cuenta
        //     tipoCuentaRadios.forEach(radio => {
        //         radio.addEventListener("change", function() {
        //             codigoCuenta1.value = tipoCuentaValores[this.value] || "0";
        //             if (nivelGrupo) {
        //                 nivelGrupo.checked = true;
        //                 toggleInput(nivelGrupo, codigoCuenta1, 1);
        //             }
        //             actualizarCodigoFinal();
        //         });
        //     });

        //     // Niveles
        //     nivelCheckboxes.forEach((checkbox, index) => {
        //         const input = document.getElementById("codigoCuenta" + (index + 1));
        //         const maxLength = [1, 1, 2, 2, 4][index];

        //         checkbox.addEventListener("change", () => {
        //             toggleInput(checkbox, input, maxLength);

        //             if (checkbox === nivelSubCuenta) {
        //                 if (checkbox.checked) {
        //                     checkMovimiento.checked = true;
        //                     checkMovimiento.disabled = true;
        //                 } else {
        //                     checkMovimiento.checked = false;
        //                     checkMovimiento.disabled = false;
        //                 }
        //             }
        //         });
        //     });

        //     // Modal comportamiento
        //     const modalCrearCuenta = document.getElementById("modalAdicionarCuenta");
        //     const cancelarButton = document.querySelector(".btn-secondary");

        //     function limpiarFormulario() {
        //         tipoCuentaRadios.forEach(radio => radio.checked = false);
        //         nivelCheckboxes.forEach(checkbox => checkbox.checked = false);

        //         [codigoCuenta1, codigoCuenta2, codigoCuenta3, codigoCuenta4, codigoCuenta5].forEach((input,
        //             index) => {
        //             const longitudes = [1, 1, 2, 2, 4];
        //             input.value = "0".repeat(longitudes[index]);
        //             input.disabled = true;
        //         });

        //         codigoCuentaFinal.value = "";
        //         checkMovimiento.checked = false;
        //         checkMovimiento.disabled = false;
        //     }

        //     // modalCrearCuenta.addEventListener("hidden.bs.modal", limpiarFormulario);

        //     cancelarButton.addEventListener("click", function(event) {
        //         event.preventDefault();
        //         Swal.fire({
        //             title: "¿Cancelar la adición?",
        //             text: "Se perderán todos los datos ingresados.",
        //             icon: "warning",
        //             showCancelButton: true,
        //             confirmButtonText: "Sí, cancelar",
        //             cancelButtonText: "No, continuar",
        //             reverseButtons: true
        //         }).then((result) => {
        //             if (result.isConfirmed) {
        //                 limpiarFormulario();
        //                 let modalInstance = bootstrap.Modal.getInstance(modalCrearCuenta);
        //                 modalInstance.hide();
        //                 Swal.fire("Cancelado", "Los datos han sido eliminados.", "success");
        //             }
        //         });
        //     });

        //     document.getElementById("crearCuentaForm").addEventListener("submit", function(event) {
        //         event.preventDefault();
        //         Swal.fire({
        //             title: "¿Crear cuenta?",
        //             text: "Se guardará la nueva cuenta en el sistema.",
        //             icon: "question",
        //             showCancelButton: true,
        //             confirmButtonText: "Sí, crear",
        //             cancelButtonText: "No, cancelar"
        //         }).then((result) => {
        //             if (result.isConfirmed) {
        //                 this.submit();
        //                 Swal.fire("¡Cuenta Creada!", "La cuenta ha sido agregada exitosamente.",
        //                     "success");
        //             }
        //         });
        //     });

        //     actualizarCodigoFinal();
        // });
        document.addEventListener('DOMContentLoaded', function() {
            const tipoCuentaSelect = document.getElementById('tipo_cuenta');
            const parentSelect = document.getElementById('parent_id');
            const originalOptions = Array.from(parentSelect.options);

            function filtrarOpciones() {
                const tipoSeleccionado = tipoCuentaSelect.value;
                parentSelect.innerHTML = '';

                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = '-- Ninguna (Cuenta Raíz) --';
                parentSelect.appendChild(defaultOption);

                if (!tipoSeleccionado) {
                    parentSelect.disabled = true;
                    return;
                }

                parentSelect.disabled = false;

                originalOptions.forEach(option => {
                    if (option.dataset && option.dataset.tipo === tipoSeleccionado) {
                        parentSelect.appendChild(option);
                    }
                });
            }

            tipoCuentaSelect.addEventListener('change', filtrarOpciones);
            filtrarOpciones(); // Ejecutar una vez al cargar la página
        });
    </script>
@endsection
