@extends('layouts.admin')

@section('content')
    <div class="h-screen bg-white">
        <div class="bg-[var(--header-bg)] pt-10 pb-[84px]"></div>

        <div class="max-w-3xl mx-auto -mt-20 px-6">
            <div class="bg-white shadow-lg rounded-xl p-6">
                <h2 class="text-2xl font-semibold text-blue-600 mb-4">Datos de la Empresa</h2>

                <form id="empresaForm" method="POST" action="{{ route('empresas.update', $empresa->id) }}">
                    @csrf
                    @method('PUT')

                    <dl class="space-y-4">

                        {{-- Nombre --}}
                        <div class="mb-4">
                            <label for="empresaName" class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                            <input id="empresaName" name="nombre" type="text" required
                                value="{{ old('nombre', $empresa->nombre) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:ring-2 focus:outline-none bg-gray-100"
                                readonly>
                        </div>

                        {{-- Tipo de documento y Número de documento --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="tipo_documento" class="block text-sm font-medium text-gray-700 mb-1">Tipo de
                                    documento</label>
                                <select id="tipo_documento" name="tipo_documento"
                                    class="w-full border border-gray-300 rounded p-2 bg-gray-100 pointer-events-none">
                                    <option value="CI" @selected(old('tipo_documento', $empresa->tipo_documento) == 'CI')>CI</option>
                                    <option value="NIT" @selected(old('tipo_documento', $empresa->tipo_documento) == 'NIT')>NIT</option>
                                </select>
                            </div>

                            <div>
                                <label for="numero_documento" class="block text-sm font-medium text-gray-700 mb-1">Número de
                                    documento</label>
                                <input id="numero_documento" name="numero_documento" type="text" required
                                    value="{{ old('numero_documento', $empresa->numero_documento) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:ring-2 focus:outline-none bg-gray-100"
                                    readonly>
                            </div>
                        </div>

                        {{-- Dirección --}}
                        <div class="mb-4">
                            <label for="direccion" class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                            <input id="direccion" name="direccion" type="text"
                                value="{{ old('direccion', $empresa->direccion) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:ring-2 focus:outline-none bg-gray-100"
                                readonly>
                        </div>

                        {{-- Casa Matriz --}}
                        <div class="mb-4 flex items-center">
                            <input type="hidden" name="casa_matriz" value="0">
                            <input id="casa_matriz" name="casa_matriz" type="checkbox" value="1"
                                @checked(old('casa_matriz', $empresa->casa_matriz))
                                class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" disabled>
                            <label for="casa_matriz" class="ml-2 block text-sm text-gray-700">¿Es casa matriz?</label>
                        </div>

                        {{-- Sucursal --}}
                        <div class="mb-4" id="sucursalContainer">
                            <label for="sucursal" class="block text-sm font-medium text-gray-700 mb-1">Nombre de
                                sucursal</label>
                            <input type="text" name="sucursal" id="sucursal"
                                value="{{ old('sucursal', $empresa->sucursal) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:ring-2 focus:outline-none bg-gray-100"
                                readonly>
                        </div>

                        {{-- Ciudad y Teléfono --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="ciudad" class="block text-sm font-medium text-gray-700 mb-1">Ciudad</label>
                                <input id="ciudad" name="ciudad" type="text"
                                    value="{{ old('ciudad', $empresa->ciudad) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:ring-2 focus:outline-none bg-gray-100"
                                    readonly>
                            </div>

                            <div>
                                <label for="telefono" class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                                <input id="telefono" name="telefono" type="text"
                                    value="{{ old('telefono', $empresa->telefono) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:ring-2 focus:outline-none bg-gray-100"
                                    readonly>
                            </div>
                        </div>

                        {{-- Tipo de empresa --}}
                        <div class="mb-4">
                            <label for="tipo_empresa" class="block text-sm font-medium text-gray-700 mb-1">Tipo de
                                empresa</label>
                            <select id="tipo_empresa" name="tipo_empresa"
                                class="w-full border border-gray-300 rounded p-2 bg-gray-100 pointer-events-none">
                                <option value="" disabled @selected(old('tipo_empresa', $empresa->tipo_empresa) == null)>Seleccione</option>
                                <option value="Mineria" @selected(old('tipo_empresa', $empresa->tipo_empresa) == 'Mineria')>Mineria</option>
                                <option value="Comercial" @selected(old('tipo_empresa', $empresa->tipo_empresa) == 'Comercial')>Comercial</option>
                                <option value="Agropecuaria" @selected(old('tipo_empresa', $empresa->tipo_empresa) == 'Agropecuaria')>Agropecuaria</option>
                                <option value="Industrial" @selected(old('tipo_empresa', $empresa->tipo_empresa) == 'Industrial')>Industrial</option>
                            </select>
                        </div>

                        {{-- Fechas de inicio y fin --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 mb-1">Fecha de
                                    Inicio</label>
                                <input id="fecha_inicio" name="fecha_inicio" type="date"
                                    value="{{ old('fecha_inicio', $empresa->fecha_inicio) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:ring-2 focus:outline-none bg-gray-100"
                                    readonly>
                            </div>

                            <div>
                                <label for="fecha_fin" class="block text-sm font-medium text-gray-700 mb-1">Fecha de
                                    Fin</label>
                                <input id="fecha_fin" name="fecha_fin" type="date"
                                    value="{{ old('fecha_fin', $empresa->fecha_fin) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:ring-2 focus:outline-none bg-gray-100"
                                    readonly>
                            </div>
                        </div>

                    </dl>

                    {{-- Botones --}}
                    <div class="flex justify-end space-x-2 mt-6">
                        <button type="button" id="editBtn"
                            class="bg-[var(--header-bg)] text-white px-4 py-2 rounded hover:bg-blue-700">
                            Editar
                        </button>

                        <button type="submit" id="saveBtn"
                            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 hidden">
                            Guardar
                        </button>

                        <button type="button" id="cancelBtn"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400 hidden">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('empresaForm');
            const inputs = form.querySelectorAll('input, select');
            const editBtn = document.getElementById('editBtn');
            const saveBtn = document.getElementById('saveBtn');
            const cancelBtn = document.getElementById('cancelBtn');

            const casaMatriz = document.getElementById('casa_matriz');
            const sucursalContainer = document.getElementById('sucursalContainer');
            const sucursal = document.getElementById('sucursal');

            // Guardar valores originales
            const originalValues = {};
            inputs.forEach(input => originalValues[input.name] = input.value);

            // Función para mostrar/ocultar sucursal según casa matriz
            function toggleSucursalField() {
                if (casaMatriz.checked) {
                    sucursalContainer.style.display = 'none';
                    sucursal.value = '';
                } else {
                    sucursalContainer.style.display = 'block';
                }
            }

            // Botón Editar
            editBtn.addEventListener('click', () => {
                inputs.forEach(input => {
                    input.removeAttribute('readonly');
                    input.disabled = false;
                    input.style.pointerEvents = 'auto';
                    input.classList.remove('bg-gray-100');
                });
                editBtn.classList.add('hidden');
                saveBtn.classList.remove('hidden');
                cancelBtn.classList.remove('hidden');
                toggleSucursalField();
            });

            // Cambios de casa matriz
            casaMatriz.addEventListener('change', toggleSucursalField);

            // Botón Cancelar
            cancelBtn.addEventListener('click', () => {
                inputs.forEach(input => {
                    input.value = originalValues[input.name];
                    input.setAttribute('readonly', true);
                    input.disabled = true;
                    input.style.pointerEvents = 'none';
                    input.classList.add('bg-gray-100');
                });
                editBtn.classList.remove('hidden');
                saveBtn.classList.add('hidden');
                cancelBtn.classList.add('hidden');
                toggleSucursalField();
            });

            // Inicializar el estado al cargar
            toggleSucursalField();
        });
    </script>
@endsection
