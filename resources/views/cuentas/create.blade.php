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
                            data-nivel="{{ $padre->nivel }}"
                            {{ old('parent_id', $cuenta->parent_id ?? '') == $padre->id_cuenta ? 'selected' : '' }}>
                            {{ $padre->nombre_cuenta }} ({{ $padre->codigo_cuenta }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Es movimiento --}}
            <div class="flex items-center gap-3">
                <input type="checkbox" name="es_movimiento" id="es_movimiento" value="1"
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
        document.addEventListener('DOMContentLoaded', function() {
            const tipoCuentaSelect = document.getElementById('tipo_cuenta');
            const parentSelect = document.getElementById('parent_id');
            const checkboxMovimiento = document.getElementById('es_movimiento');
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

            function evaluarCheckboxMovimiento() {
                const selectedOption = parentSelect.options[parentSelect.selectedIndex];
                const nivel = parseInt(selectedOption.dataset.nivel || '0');

                // Si el padre tiene nivel 3 o menor, deshabilitar checkbox
                if (!isNaN(nivel) && nivel < 3) {
                    checkboxMovimiento.checked = false;
                    checkboxMovimiento.disabled = true;
                } else {
                    // Solo habilita si la cuenta NO tiene hijos (en edición)
                    const isEditMode = {{ $modo === 'editar' ? 'true' : 'false' }};
                    if (isEditMode) {
                        const tieneHijos = {{ isset($cuenta) && $cuenta->hasChildren() ? 'true' : 'false' }};
                        checkboxMovimiento.disabled = tieneHijos;
                    } else {
                        checkboxMovimiento.disabled = false;
                    }
                }
            }

            parentSelect.addEventListener('change', evaluarCheckboxMovimiento);
            evaluarCheckboxMovimiento();
            tipoCuentaSelect.addEventListener('change', filtrarOpciones);
            filtrarOpciones(); // Ejecutar una vez al cargar la página
        });
    </script>
@endsection
