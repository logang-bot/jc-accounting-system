@php
    $modo = $modo ?? 'crear';
@endphp

@section('content')
    <div class="bg-white h-screen">
        <div class="w-full mx-auto">
            <div class="flex flex-col gap-6">
                <!-- Header -->
                <div class="flex justify-between items-center bg-[var(--header-bg)] px-10 py-5">
                    <h3 class="text-white text-2xl font-semibold">Gestion de empresas</h3>
                </div>

                <form id="cuenta-form"
                    action="{{ $modo === 'editar' ? route('cuentas.update', $cuenta->id_cuenta) : route('cuentas.store') }}"
                    method="POST" class="space-y-4">
                    @csrf
                    @if ($modo === 'editar')
                        @method('PUT')
                    @endif

                    {{-- Nombre de la cuenta --}}
                    <div>
                        <label for="nombre_cuenta" class="block text-sm font-medium text-gray-700">Nombre de la
                            Cuenta</label>
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
                        <select name="parent_id" id="parent_id"
                            class="mt-1 block w-full border border-gray-300 rounded px-3 py-2">
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

                    {{-- NIT / CI --}}
                    <div class="flex-1">
                        <label for="documento" class="block text-sm font-medium text-gray-700 mb-1">
                            Número de documento
                        </label>
                        <input type="text" name="numero_documento" id="documento" required
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:ring-2 focus:outline-none">
                    </div>
            </div>

            {{-- Dirección --}}
            <div class="mb-4">
                <label for="direccion" class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                <input type="text" name="direccion" id="direccion"
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:ring-2 focus:outline-none" />
            </div>
            {{-- Casa Matriz --}}
            <div class="mb-4 flex items-center">
                <input type="hidden" name="casa_matriz" value="0">
                <input id="casa_matriz" name="casa_matriz" type="checkbox" value="1"
                    class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <label for="casa_matriz" class="ml-2 block text-sm text-gray-700">¿Es casa
                    matriz?</label>
            </div>

            {{-- Ciudad y Teléfono --}}
            <div class="flex gap-4 mb-4">
                {{-- Ciudad --}}
                <div class="flex-1">
                    <label for="ciudad" class="block text-sm font-medium text-gray-700 mb-1">Ciudad</label>
                    <input type="text" name="ciudad" id="ciudad"
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:ring-2 focus:outline-none" />
                </div>

                {{-- Teléfono --}}
                <div class="flex-1">
                    <label for="telefono" class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                    <input type="text" name="telefono" id="telefono"
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:ring-2 focus:outline-none" />
                </div>
            </div>

            {{-- Tipo de empresa --}}
            <div class="mb-6">
                <label for="periodo" class="block text-sm font-medium text-gray-700 mb-1">Tipo de
                    empresa</label>
                <select id="periodo" name="periodo" required
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:ring-2 focus:outline-none">
                    <option value="" disabled selected>Seleccione</option>
                    <option value="Mineria">Minera</option>
                    <option value="Comercial">Comercial</option>
                    <option value="Agropecuaria">Agropecuaria</option>
                    <option value="Industrial">Industrial</option>
                </select>
            </div>

            <div class="flex gap-4 mb-4">
                {{-- Fecha Inicio --}}
                <div class="flex-1">
                    <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 mb-1">
                        Fecha de Inicio
                    </label>
                    <input id="fecha_inicio" name="fecha_inicio" type="date" required
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:ring-2 focus:outline-none" />
                </div>

                {{-- Fecha Fin --}}
                <div class="flex-1">
                    <label for="fecha_fin" class="block text-sm font-medium text-gray-700 mb-1">
                        Fecha de Fin
                    </label>
                    <input id="fecha_fin" name="fecha_fin" type="date"
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:ring-2 focus:outline-none" />
                </div>
            </div>

            {{-- Botón de Envío --}}
            <button type="submit"
                class="w-full bg-[var(--header-bg)] hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded cursor-pointer">
                Crear Empresa
            </button>
            </form>
        </div>
    </div>
@endhasanyrole

<div class="flex-1">
    @if ($empresas->isEmpty())
        <div class="flex flex-col items-center justify-center py-10 text-center text-gray-400">
            <x-carbon-building class="w-10 h-10 mb-3 opacity-70" />
            <p class="text-lg font-semibold">No hay empresas registradas</p>
            <p class="text-sm text-gray-500 mb-4">Agrega una nueva empresa para comenzar</p>
        </div>
    @else
        <!-- Table -->
        <div class="flex flex-row w-full gap-4">
            <table class="overflow-y-auto max-h-[760px] flex-1">
                <thead class="bg-gray-800 text-white text-sm font-semibold">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-white">
                            Nombre</th>
                        <th scope="col"
                            class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase dark:text-white">
                            Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($empresas as $empresa)
                        <tr class="odd:bg-white even:bg-gray-100/75 hover:bg-gray-100"
                            data-empresa-id="{{ $empresa->id }}">
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 flex flex-row items-center gap-1">
                                {{ $empresa->name }}
                                @if ($empresa->activa)
                                    <span
                                        class="inline-flex items-center px-2 py-1 text-sm font-semibold text-green-800 bg-green-300 rounded-full">
                                        Activa
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2 py-1 text-sm font-semibold text-white bg-red-800 rounded-full">
                                        Archivada
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                @if ($empresa->activa)
                                    <a href="{{ route('show.empresas.detail', $empresa->id) }}"
                                        class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-blue-600 hover:text-blue-800 focus:outline-hidden focus:text-blue-800 disabled:opacity-50 disabled:pointer-events-none dark:text-yellow-500 dark:hover:text-blue-400 dark:focus:text-blue-400">Ingresar</a>
                                @endif
                                @role('Administrator')
                                    <button type="button" data-empresa-id="{{ $empresa->id }}"
                                        class="archive-btn inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-blue-600 hover:text-blue-800 focus:outline-hidden focus:text-blue-800 disabled:opacity-50 disabled:pointer-events-none dark:text-gray-500 dark:hover:text-blue-400 dark:focus:text-blue-400 cursor-pointer">{{ $empresa->activa ? 'Archivar' : 'Activar' }}</button>
                                    <form id="archive-form-{{ $empresa->id }}"
                                        action="{{ route('empresas.archive', $empresa->id) }}" method="POST"
                                        class="hidden">
                                        @csrf
                                        @method('POST')
                                    </form>

                                    <button type="button" data-empresa-id="{{ $empresa->id }}"
                                        class="delete-btn inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-blue-600 hover:text-blue-800 focus:outline-hidden focus:text-blue-800 disabled:opacity-50 disabled:pointer-events-none dark:text-red-500 dark:hover:text-blue-400 dark:focus:text-blue-400 cursor-pointer">Eliminar</button>
                                    <form id="delete-form-{{ $empresa->id }}"
                                        action="{{ route('empresas.destroy', $empresa->id) }}" method="POST"
                                        class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @endrole
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
</div>
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
