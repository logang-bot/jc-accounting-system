@php
    $modo = $modo ?? 'crear';
@endphp

<div class="max-w-2xl mx-auto p-6 m-6 bg-white rounded-xl shadow" x-data="{
    esMovimiento: {{ old('es_movimiento', $cuenta->es_movimiento ?? false) ? 'true' : 'false' }}
}">
    <h2 class="text-2xl font-bold mb-4">{{ $modo === 'editar' ? 'Editar cuenta' : 'Crear Cuenta Contable' }}</h2>

    <form id="cuenta-form"
        action="{{ $modo === 'editar' ? route('cuentas.update', $cuenta->id_cuenta) : route('cuentas.store') }}"
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
            <select name="tipo_cuenta" id="tipo_cuenta"
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
            <select name="parent_id" id="parent_id" class="mt-1 block w-full border border-gray-300 rounded px-3 py-2">
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
                <input type="checkbox" name="es_movimiento" id="es_movimiento" value="1"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                    x-model="esMovimiento">
                <span class="ml-2 text-sm text-gray-700">¿Es cuenta de movimiento?</span>
            </label>
        </div>

        {{-- Moneda Principal --}}
        <div class="mb-4">
            <label for="moneda_principal" class="block text-sm font-medium text-gray-700">Moneda Principal</label>
            <select name="moneda_principal" id="moneda_principal" :disabled="!esMovimiento"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-500">
                <option value="">Selecciona una moneda</option>
                <option value="BOB" @selected(old('moneda_principal', $cuenta?->moneda_principal ?? '') === 'BOB')>BOB</option>
                <option value="USD" @selected(old('moneda_principal', $cuenta?->moneda_principal ?? '') === 'USD')>USD</option>
            </select>
            <div class="mt-2 text-xs text-gray-500" x-show="!esMovimiento">
                ⚠️ Marca esta cuenta como de movimiento para seleccionar una moneda.
            </div>
        </div>

        <div>
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded cursor-pointer">
                {{ $modo === 'editar' ? 'Actualizar cuenta' : 'Crear cuenta' }}
            </button>
        </div>
        <input type="hidden" name="redirect_to" value="{{ route('show.cuentas.home') }}">
    </form>
</div>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('cuenta-form').addEventListener('submit', function(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);

        fetch(form.action, {
                method: form.method,
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(async response => {
                if (!response.ok) {
                    const errorText = await response.text();
                    console.error(errorText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo crear la cuenta. Revisa los datos.'
                    });
                } else {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: 'Cuenta creada correctamente.',
                        showConfirmButton: false,
                        timer: 1500,
                        willClose: () => {
                            // Redirigir a la lista de cuentas
                            window.location.href = "{{ route('show.cuentas.home') }}";
                        }
                    });
                }
            })
            .catch(error => {
                console.error(error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error en la solicitud.'
                });
            });
    });
</script>
