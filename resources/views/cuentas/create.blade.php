@extends('layouts.admin')

@section('content')
    <div class="max-w-2xl mx-auto p-6 m-6 bg-white rounded-xl shadow" x-data="{
        esMovimiento: {{ old('es_movimiento', $cuenta->es_movimiento ?? false) ? 'true' : 'false' }},
        isNivelValido: {{ isset($cuenta) && in_array($cuenta->nivel, [4, 5]) ? 'true' : 'false' }}
    }">
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
                            data-nivel="{{ $padre->nivel }}"
                            {{ old('parent_id', $cuenta->parent_id ?? '') == $padre->id_cuenta ? 'selected' : '' }}>
                            {{ $padre->nombre_cuenta }} ({{ $padre->codigo_cuenta }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Checkbox: Es Movimiento --}}
            <div class="mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" id="es_movimiento" value="1"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                        x-model="esMovimiento">
                    <span class="ml-2 text-sm text-gray-700">¿Es cuenta de movimiento?</span>
                </label>
                @error('es_movimiento')
                    <span class="text-sm text-red-600">{{ $message }}</span>
                @enderror
            </div>


            {{-- Moneda Principal --}}
            <div class="mb-4">
                <label for="moneda_principal" class="block text-sm font-medium text-gray-700">Moneda Principal</label>

                {{-- @if (isset($cuenta) && in_array($cuenta->nivel, [4, 5])) --}}
                {{-- Dropdown shown only in edit + nivel 4/5 --}}
                <select name="moneda_principal" id="moneda_principal" :disabled="!esMovimiento"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-500">
                    <option value="">Selecciona una moneda</option>
                    <option value="BOB" @selected(old('moneda_principal', $cuenta?->moneda_principal) === 'BOB')>BOB</option>
                    <option value="USD" @selected(old('moneda_principal', $cuenta?->moneda_principal) === 'USD')>USD</option>
                </select>

                {{-- Conditional help message when disabled --}}
                <div class="mt-2 text-xs text-gray-500" x-show="!esMovimiento">
                    ⚠️ Marca esta cuenta como de movimiento para seleccionar una moneda.
                </div>

                @error('moneda_principal')
                    <span class="text-sm text-red-600">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded">
                    {{ $modo === 'editar' ? 'Actualizar cuenta' : 'Crear cuenta' }}
                </button>
            </div>
        </form>

    </div>

    {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tipoCuentaSelect = document.getElementById('tipo_cuenta');
            const parentSelect = document.getElementById('parent_id');
            const checkboxMovimiento = document.getElementById('es_movimiento');
            const originalOptions = Array.from(parentSelect.options);
            const monedaSelect = document.querySelector('#moneda_principal');

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

            function evaluarCheckboxMovimientoYMonedaPrincipal() {
                const selectedOption = parentSelect.options[parentSelect.selectedIndex];
                const nivel = parseInt(selectedOption.dataset.nivel || '0');

                // Si el padre tiene nivel 3 o menor, deshabilitar checkbox
                if (!isNaN(nivel) && nivel < 3) {
                    checkboxMovimiento.checked = false;
                    checkboxMovimiento.disabled = true;
                    monedaSelect.selectedIndex = 0;
                    monedaSelect.disabled = !checkboxMovimiento.checked
                } else {
                    // Solo habilita si la cuenta NO tiene hijos (en edición)
                    const isEditMode = {{ $modo === 'editar' ? 'true' : 'false' }};
                    if (isEditMode) {
                        const tieneHijos = {{ isset($cuenta) && $cuenta->hasChildren() ? 'true' : 'false' }};
                        checkboxMovimiento.disabled = tieneHijos;
                        monedaSelect.disabled = !checkboxMovimiento.checked
                    } else {
                        checkboxMovimiento.disabled = false;
                        monedaSelect.disabled = !checkboxMovimiento.checked
                    }
                }
            }

            parentSelect.addEventListener('change', evaluarCheckboxMovimientoYMonedaPrincipal);
            parentSelect.addEventListener('change', evaluarCheckboxMovimientoYMonedaPrincipal);
            evaluarCheckboxMovimientoYMonedaPrincipal();
            tipoCuentaSelect.addEventListener('change', filtrarOpciones);
            filtrarOpciones(); // Ejecutar una vez al cargar la página
        });
    </script>
@endsection
