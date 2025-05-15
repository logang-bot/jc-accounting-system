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
                                <h2 class="text-2xl font-semibold text-gray-800">
                                    Lista de Empresas
                                </h2>
                            </div>

                            <!-- Right Side Content -->
                            <div class="flex flex-row  gap-3">

                                <div class="flex flex-col items-stretch gap-4">
                                    <!-- Button -->
                                    <button type="button"
                                        class="bg-green-600/20 hover:bg-green-600/60 text-white font-semibold py-2 px-4 rounded w-full "
                                        aria-controls="create-empresa-modal" data-hs-overlay="#create-empresa-modal">
                                        <x-carbon-add-filled />
                                        Crear Empresa
                                    </button>

                                    <!-- Logout Button -->
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="bg-red-600/20 hover:bg-red-600/60 text-white font-semibold py-2 px-4 rounded w-full">
                                            <x-carbon-exit />
                                            Cerrar Sesión
                                        </button>
                                    </form>
                                </div>

                                <!-- Table -->
                                <div class="p-3 bg-black/30 rounded-2xl">
                                    <table class="min-w-80 text-sm text-left ">
                                        <thead class=" text-white">
                                            <tr>
                                                <th class="px-4 py-2 border-b">{{ __('Nombre') }}</th>
                                                <th class="px-4 py-2 border-b">{{ __('Opciones') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-white">
                                            @foreach ($empresas as $empresa)
                                                <tr class="hover:bg-black/20 transition-colors"
                                                    data-empresa-id="{{ $empresa->id }}">
                                                    <td class="py-4 px-4 align-middle">
                                                        {{ $empresa->name }}
                                                    </td>
                                                    <td class="px-4">
                                                        <div class="flex flex-row">
                                                            <a href="{{ route('show.empresas.home', $empresa->id) }}"
                                                                class="hover:bg-green-600 text-white py-1 px-3 rounded text-xs">
                                                                <x-carbon-arrow-right class="w-6 h-6 text-white" />
                                                            </a>
                                                            <button data-empresa-id="{{ $empresa->id }}"
                                                                class="delete-btn hover:bg-red-600/50 text-white py-1 px-3 rounded text-xs">
                                                                <x-carbon-trash-can class="w-6 h-6 text-white" />
                                                            </button>
                                                            <form id="delete-form-{{ $empresa->id }}"
                                                                action="{{ route('empresas.destroy', $empresa->id) }}"
                                                                method="POST" class="hidden">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                        </div>
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
