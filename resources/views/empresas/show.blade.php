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

                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm text-gray-700">
                        {{-- Nombre --}}
                        <div>
                            <dt class="font-medium">Nombre</dt>
                            <dd>
                                <input type="text" name="name" value="{{ old('name', $empresa->name) }}"
                                    class="w-full border-gray-300 rounded-md shadow-sm disabled:bg-gray-100 p-2" disabled />
                            </dd>
                        </div>

                        {{-- NIT --}}
                        <div>
                            <dt class="font-medium">NIT</dt>
                            <dd>
                                <input type="text" name="nit" value="{{ old('nit', $empresa->nit) }}"
                                    class="w-full border-gray-300 rounded-md shadow-sm disabled:bg-gray-100 p-2" disabled />
                            </dd>
                        </div>

                        {{-- Dirección --}}
                        <div>
                            <dt class="font-medium">Dirección</dt>
                            <dd>
                                <input type="text" name="direccion" value="{{ old('direccion', $empresa->direccion) }}"
                                    class="w-full border-gray-300 rounded-md shadow-sm disabled:bg-gray-100 p-2" disabled />
                            </dd>
                        </div>

                        {{-- Ciudad --}}
                        <div>
                            <dt class="font-medium">Ciudad</dt>
                            <dd>
                                <input type="text" name="ciudad" value="{{ old('ciudad', $empresa->ciudad) }}"
                                    class="w-full border-gray-300 rounded-md shadow-sm disabled:bg-gray-100 p-2" disabled />
                            </dd>
                        </div>

                        {{-- Provincia --}}
                        <div>
                            <dt class="font-medium">Provincia</dt>
                            <dd>
                                <input type="text" name="provincia" value="{{ old('provincia', $empresa->provincia) }}"
                                    class="w-full border-gray-300 rounded-md shadow-sm disabled:bg-gray-100 p-2" disabled />
                            </dd>
                        </div>

                        {{-- Teléfono --}}
                        <div>
                            <dt class="font-medium">Teléfono</dt>
                            <dd>
                                <input type="text" name="telefono" value="{{ old('telefono', $empresa->telefono) }}"
                                    class="w-full border-gray-300 rounded-md shadow-sm disabled:bg-gray-100 p-2" disabled />
                            </dd>
                        </div>

                        {{-- Celular --}}
                        <div>
                            <dt class="font-medium">Celular</dt>
                            <dd>
                                <input type="text" name="celular" value="{{ old('celular', $empresa->celular) }}"
                                    class="w-full border-gray-300 rounded-md shadow-sm disabled:bg-gray-100 p-2" disabled />
                            </dd>
                        </div>

                        {{-- Correo Electrónico --}}
                        <div>
                            <dt class="font-medium">Correo Electrónico</dt>
                            <dd>
                                <input type="email" name="correo_electronico"
                                    value="{{ old('correo_electronico', $empresa->correo_electronico) }}"
                                    class="w-full border-gray-300 rounded-md shadow-sm disabled:bg-gray-100 p-2" disabled />
                            </dd>
                        </div>

                        {{-- Periodo --}}
                        <div>
                            <dt class="font-medium">Periodo</dt>
                            <dd>
                                <select name="periodo"
                                    class="w-full border-gray-300 rounded-md shadow-sm disabled:bg-gray-100 p-2" disabled>
                                    <option value="Mineria" @selected(old('periodo', $empresa->periodo) == 'Mineria')>Mineria</option>
                                    <option value="Comercial" @selected(old('periodo', $empresa->periodo) == 'Comercial')>Comercial</option>
                                    <option value="Agropecuaria" @selected(old('periodo', $empresa->periodo) == 'Agropecuaria')>Agropecuaria</option>
                                    <option value="Industrial" @selected(old('periodo', $empresa->periodo) == 'Industrial')>Industrial</option>
                                </select>
                            </dd>
                        </div>

                        {{-- Gestión --}}
                        <div>
                            <dt class="font-medium">Gestión</dt>
                            <dd>
                                <input type="text" name="gestion" value="{{ old('gestion', $empresa->gestion) }}"
                                    class="w-full border-gray-300 rounded-md shadow-sm disabled:bg-gray-100 p-2" disabled />
                            </dd>
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
