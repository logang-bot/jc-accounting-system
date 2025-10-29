@extends('layouts.admin')

@section('content')
    <div class="bg-white h-screen">
        <div class="w-full mx-auto">
            <div class="flex flex-col gap-6">
                <!-- Header -->
                <div class="flex justify-between items-center bg-blue-600 px-10 py-5">
                    <h3 class="text-white text-2xl font-semibold">Gestion de empresas</h3>
                </div>

                @if (session('success'))
                    <div id="flash" class="p-4 text-center bg-green-50 text-green-500 font-bold">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="flex flex-row gap-5 p-6">
                    @hasanyrole('Administrator|Teacher')
                        <div class="p-6 bg-white shadow-md rounded-xl flex-1">
                            <h2 class="text-2xl font-semibold mb-6">
                                Crear empresa
                            </h2>

                            <!-- Modal Body -->
                            <div class="p-6">
                                <form id="crearEmpresaForm" method="POST" action="{{ route('empresas.store') }}">
                                    @csrf

                                    {{-- Nombre --}}
                                    <div class="mb-4">
                                        <label for="empresaName"
                                            class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                                        <input id="empresaName" name="name" type="text" required
                                            class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:ring-2 focus:outline-none" />
                                    </div>

                                    <div class="flex gap-4 mb-4">
                                        {{-- Tipo documento --}}
                                        <div class="flex-1">
                                            <label for="tipo_documento" class="block text-sm font-medium text-gray-700 mb-1">
                                                Tipo de documento
                                            </label>
                                            <select name="tipo_documento" id="tipo_documento" required
                                                class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:ring-2 focus:outline-none">
                                                <option value="" disabled selected>Seleccione</option>
                                                <option value="CI">CI</option>
                                                <option value="NIT">NIT</option>
                                            </select>
                                        </div>


                                        {{-- NIT / CI --}}
                                        <div class="flex-1">
                                            <label for="documento" class="block text-sm font-medium text-gray-700 mb-1">
                                                Número de documento
                                            </label>
                                            <input type="text" name="documento" id="documento" required
                                                class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:ring-2 focus:outline-none">
                                        </div>
                                    </div>



                                    {{-- Casa Matriz --}}
                                    <div class="mb-4 flex items-center">
                                        <input type="hidden" name="casa_matriz" value="0">
                                        <input id="casa_matriz" name="casa_matriz" type="checkbox" value="1"
                                            class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                        <label for="casa_matriz" class="ml-2 block text-sm text-gray-700">¿Es casa
                                            matriz?</label>
                                    </div>

                                    {{-- Periodo --}}
                                    <div class="mb-6">
                                        <label for="periodo"
                                            class="block text-sm font-medium text-gray-700 mb-1">Periodo</label>
                                        <select id="periodo" name="periodo" required
                                            class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:ring-2 focus:outline-none">
                                            <option value="Mineria">Mineria</option>
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
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded cursor-pointer">
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
                                <a href="{{ route('show.empresas.home') }}"
                                    class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg mt-2">
                                    <x-carbon-add class="w-5 h-5 inline-block mr-2" />
                                    Crear Empresa
                                </a>
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
                                                            action="{{ route('empresas.archive', $empresa->id) }}"
                                                            method="POST" class="hidden">
                                                            @csrf
                                                            @method('POST')
                                                        </form>

                                                        <button type="button" data-empresa-id="{{ $empresa->id }}"
                                                            class="delete-btn inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-blue-600 hover:text-blue-800 focus:outline-hidden focus:text-blue-800 disabled:opacity-50 disabled:pointer-events-none dark:text-red-500 dark:hover:text-blue-400 dark:focus:text-blue-400 cursor-pointer">Eliminar</button>
                                                        <form id="delete-form-{{ $empresa->id }}"
                                                            action="{{ route('empresas.destroy', $empresa->id) }}"
                                                            method="POST" class="hidden">
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (session('success'))
        <script>
            Swal.fire({
                title: "¡Éxito!",
                text: "{{ session('success') }}",
                icon: "success",
                confirmButtonText: "OK"
            });
        </script>
    @endif
@endsection
