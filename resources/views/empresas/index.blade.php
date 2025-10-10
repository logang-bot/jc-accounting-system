@extends('layouts.admin')

@section('content')
    <div class="bg-white h-screen">
        <div class="w-full mx-auto">
            <div class="flex flex-col gap-6">
                <!-- Header -->
                <div class="flex justify-between items-center bg-blue-600 px-10 py-5">
                    <h3 class="text-white text-2xl font-semibold">Lista de empresas</h3>
                </div>


                @if ($empresas->isEmpty())
                    <div class="flex flex-col items-center justify-center py-10 text-center text-gray-400">
                        <x-carbon-building class="w-10 h-10 mb-3 opacity-70" />
                        <p class="text-lg font-semibold">No hay empresas registradas</p>
                        <p class="text-sm text-gray-500 mb-4">Agrega una nueva empresa para comenzar</p>
                        <a href="{{ route('show.empresas.create') }}"
                            class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg mt-2">
                            <x-carbon-add class="w-5 h-5 inline-block mr-2" />
                            Crear Empresa
                        </a>
                    </div>
                @else
                    <!-- Table -->
                    <div class="px-6 flex flex-row w-full gap-4">
                        <table class="overflow-y-auto max-h-[760px] w-[40%] mx-auto">
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
