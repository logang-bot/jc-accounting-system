@extends('layouts.main')

@section('customstyles')
    @vite('resources/css/empresas.css')
@endsection

@section('customscripts')
    @vite('resources/js/empresasCreate.js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('content')
    @if (session('success'))
        <div id="flash" class="p-4 text-center bg-green-50 text-green-500 font-bold">
            {{ session('success') }}
        </div>
    @endif

    <div class="content flex items-center justify-center h-screen">
        <div>
            <div class="">
                <div class="custom-card-glass shadow-md rounded-lg">
                    <div class="p-6">
                        <div class="">
                            <!-- Title Section -->
                            <div class="justify-center text-center pb-7">
                                <h2 class="text-2xl font-semibold text-white">
                                    Lista de Empresas
                                </h2>
                            </div>

                            <!-- Right Side Content -->
                            <div class="flex flex-row gap-5 min-h-100 overflow-hidden">

                                <div class="flex flex-col items-stretch gap-4">
                                    <!-- Button -->
                                    <button type="button"
                                        class="bg-white/20 hover:bg-green-600/60 text-white font-semibold py-2 px-4 rounded w-full flex flex-row items-center gap-2"
                                        aria-controls="create-empresa-modal" data-hs-overlay="#create-empresa-modal">
                                        <x-carbon-add-filled class="w-5 h-5 " />
                                        Crear Empresa
                                    </button>

                                    <!-- Logout Button -->
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="bg-white/20 hover:bg-red-600/60 text-white font-semibold py-2 px-4 rounded w-full flex flex-row items-center gap-2">
                                            <x-carbon-exit class="w-5 h-5 " />
                                            Cerrar Sesión
                                        </button>
                                    </form>
                                </div>

                                <!-- Table -->
                                <div class="p-1">
                                    <table class="min-w-150 divide-y divide-gray-200 dark:divide-neutral-500">
                                        <thead class=" text-white">
                                            <tr>
                                                <th scope="col"
                                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-white">
                                                    Nombre</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase dark:text-white">
                                                    Opciones</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-white">
                                            @foreach ($empresas as $empresa)
                                                <tr class="odd:bg-white even:bg-gray-100 hover:bg-gray-100 dark:odd:bg-transparent dark:even:bg-neutral-600/50 dark:hover:bg-neutral-700"
                                                    data-empresa-id="{{ $empresa->id }}">
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                                                        {{ $empresa->name }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                                        <a href="{{ route('show.empresas.home', $empresa->id) }}"
                                                            class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-blue-600 hover:text-blue-800 focus:outline-hidden focus:text-blue-800 disabled:opacity-50 disabled:pointer-events-none dark:text-blue-500 dark:hover:text-blue-400 dark:focus:text-blue-400">Ingresar</a>

                                                        <button type="button" data-empresa-id="{{ $empresa->id }}"
                                                            class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-blue-600 hover:text-blue-800 focus:outline-hidden focus:text-blue-800 disabled:opacity-50 disabled:pointer-events-none dark:text-blue-500 dark:hover:text-blue-400 dark:focus:text-blue-400">Eliminar</button>
                                                        <form id="delete-form-{{ $empresa->id }}"
                                                            action="{{ route('empresas.destroy', $empresa->id) }}"
                                                            method="POST" class="hidden">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <x-modal id="create-empresa-modal">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="bg-white w-full max-w-md rounded-lg shadow-lg">

                    <!-- Modal Header -->
                    <div class="flex items-center justify-between px-6 py-4 border-b">
                        <h5 class="text-lg font-semibold">
                            {{ __('Crear Empresa') }}
                        </h5>
                        <button class="text-gray-500 hover:text-gray-700 text-xl font-bold"
                            data-hs-overlay="#create-empresa-modal">&times;</button>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-6">
                        <form id="crearEmpresaForm" method="POST" action="{{ route('empresas.store') }}">
                            @csrf
                            <div class="mb-4">
                                <label for="empresaName" class="block text-sm font-medium text-gray-700 mb-1">
                                    {{ __('Nombre') }}
                                </label>
                                <input id="empresaName" name="name" type="text" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:ring-2 focus:outline-none" />
                            </div>
                            <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
                                {{ __('Crear') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </x-modal>

    </div>
@endsection
