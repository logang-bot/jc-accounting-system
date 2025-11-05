@extends('layouts.admin')

@section('content')
    <div class="h-screen bg-white">
        <div class="bg-blue-600 pt-10 pb-[84px]"></div>

        <div class="max-w-3xl mx-auto -mt-20 px-6">
            <div class="bg-white shadow-lg rounded-xl p-6">
                <h2 class="text-2xl font-semibold text-blue-600 mb-4">Datos de la Empresa</h2>

                <form id="empresaForm" method="POST" action="{{ route('empresas.update', $empresa->id) }}">
                    @csrf
                    @method('PUT')

                    <dl class="space-y-4">
                        {{-- Nombre --}}
                        <div>
                            <dt class="font-medium">Nombre</dt>
                            <dd>
                                <input type="text" name="name" value="{{ old('name', $empresa->name) }}"
                                    class="w-full border-gray-300 rounded-md shadow-sm disabled:bg-gray-100 p-2" disabled />
                            </dd>
                        </div>

                        {{-- Tipo de documento y Número de documento --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <dt class="font-medium">Tipo de documento</dt>
                                <dd>
                                    <select name="tipo_documento" required disabled
                                        class="w-full border-gray-300 rounded-md shadow-sm disabled:bg-gray-100 p-2">
                                        <option value="CI" @selected(old('tipo_documento', $empresa->tipo_documento) == 'CI')>CI</option>
                                        <option value="NIT" @selected(old('tipo_documento', $empresa->tipo_documento) == 'NIT')>NIT</option>
                                    </select>
                                </dd>
                            </div>

                            <div>
                                <dt class="font-medium">Número de documento</dt>
                                <dd>
                                    <input type="text" name="numero_documento"
                                        value="{{ old('numero_documento', $empresa->numero_documento) }}"
                                        class="w-full border-gray-300 rounded-md shadow-sm disabled:bg-gray-100 p-2"
                                        disabled />

                                </dd>
                            </div>
                        </div>

                        {{-- Dirección --}}
                        <div>
                            <dt class="font-medium">Dirección</dt>
                            <dd>
                                <input type="text" name="direccion" value="{{ old('direccion', $empresa->direccion) }}"
                                    class="w-full border-gray-300 rounded-md shadow-sm disabled:bg-gray-100 p-2" disabled />
                            </dd>
                        </div>

                        {{-- Casa Matriz --}}
                        <div>
                            <dt class="font-medium">Casa Matriz</dt>
                            <dd>
                                <input type="checkbox" name="casa_matriz" disabled @checked($empresa->casa_matriz)
                                    class="h-4 w-4 text-blue-600 border-gray-300 rounded disabled:bg-gray-100">
                            </dd>
                        </div>

                        {{-- Ciudad y Teléfono --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <dt class="font-medium">Ciudad</dt>
                                <dd>
                                    <input type="text" name="ciudad" value="{{ old('ciudad', $empresa->ciudad) }}"
                                        class="w-full border-gray-300 rounded-md shadow-sm disabled:bg-gray-100 p-2"
                                        disabled />
                                </dd>
                            </div>

                            <div>
                                <dt class="font-medium">Teléfono</dt>
                                <dd>
                                    <input type="text" name="telefono" value="{{ old('telefono', $empresa->telefono) }}"
                                        class="w-full border-gray-300 rounded-md shadow-sm disabled:bg-gray-100 p-2"
                                        disabled />
                                </dd>
                            </div>
                        </div>

                        {{-- Tipo de empresa --}}
                        <div>
                            <dt class="font-medium">Tipo de empresa</dt>
                            <dd>
                                <select name="periodo"
                                    class="w-full border-gray-300 rounded-md shadow-sm disabled:bg-gray-100 p-2" disabled>
                                    <option value="" disabled @selected(old('periodo', $empresa->periodo) == null)>Seleccione</option>
                                    <option value="Mineria" @selected(old('periodo', $empresa->periodo) == 'Mineria')>Mineria</option>
                                    <option value="Comercial" @selected(old('periodo', $empresa->periodo) == 'Comercial')>Comercial</option>
                                    <option value="Agropecuaria" @selected(old('periodo', $empresa->periodo) == 'Agropecuaria')>Agropecuaria</option>
                                    <option value="Industrial" @selected(old('periodo', $empresa->periodo) == 'Industrial')>Industrial</option>
                                </select>
                            </dd>
                        </div>

                        {{-- Fechas de inicio y fin --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <dt class="font-medium">Fecha de Inicio</dt>
                                <dd>
                                    <input type="date" name="fecha_inicio"
                                        value="{{ old('fecha_inicio', $empresa->fecha_inicio) }}"
                                        class="w-full border-gray-300 rounded-md shadow-sm disabled:bg-gray-100 p-2"
                                        disabled />
                                </dd>
                            </div>

                            <div>
                                <dt class="font-medium">Fecha de Fin</dt>
                                <dd>
                                    <input type="date" name="fecha_fin"
                                        value="{{ old('fecha_fin', $empresa->fecha_fin) }}"
                                        class="w-full border-gray-300 rounded-md shadow-sm disabled:bg-gray-100 p-2"
                                        disabled />
                                </dd>
                            </div>
                        </div>
                    </dl>


                    <div class="flex justify-end space-x-2 mt-6">
                        <button type="button" id="editBtn"
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
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
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('empresaForm');
            const inputs = form.querySelectorAll('input, select');
            const editBtn = document.getElementById('editBtn');
            const saveBtn = document.getElementById('saveBtn');
            const cancelBtn = document.getElementById('cancelBtn');

            // Almacenar valores originales por si se cancela
            const originalValues = {};
            inputs.forEach(input => {
                originalValues[input.name] = input.value;
            });

            editBtn.addEventListener('click', () => {
                inputs.forEach(input => input.disabled = false);
                editBtn.classList.add('hidden');
                saveBtn.classList.remove('hidden');
                cancelBtn.classList.remove('hidden');
            });

            cancelBtn.addEventListener('click', () => {
                inputs.forEach(input => {
                    input.value = originalValues[input.name];
                    input.disabled = true;
                });
                editBtn.classList.remove('hidden');
                saveBtn.classList.add('hidden');
                cancelBtn.classList.add('hidden');
            });

            form.addEventListener('submit', () => {
                inputs.forEach(input => input.disabled =
                    false); // Asegurarse que los inputs no estén deshabilitados al enviar
            });
        });
    </script>
@endsection
