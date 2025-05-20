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

                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $empresa->name) }}"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm disabled:bg-gray-100"
                            disabled>
                    </div>

                    <!-- Puedes agregar más campos de empresa aquí -->

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
            const inputs = form.querySelectorAll('input');
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
