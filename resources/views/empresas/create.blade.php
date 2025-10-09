@extends('layouts.admin')


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

    <div class="flex items-center justify-center h-screen">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white w-full max-w-md rounded-lg shadow-lg">

                <!-- Modal Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b">
                    <h5 class="text-lg font-semibold">
                        Crear empresa
                    </h5>
                </div>

                <!-- Modal Body -->
                <div class="p-6">
                    <form id="crearEmpresaForm" method="POST" action="{{ route('empresas.store') }}">
                        @csrf

                        {{-- Nombre --}}
                        <div class="mb-4">
                            <label for="empresaName" class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                            <input id="empresaName" name="name" type="text" required
                                class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:ring-2 focus:outline-none" />
                        </div>

                        {{-- NIT --}}
                        <div class="mb-4">
                            <label for="nit" class="block text-sm font-medium text-gray-700 mb-1">NIT</label>
                            <input id="nit" name="nit" type="text"
                                class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:ring-2 focus:outline-none" />
                        </div>

                        {{-- Botón de Envío --}}
                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
                            Crear Empresa
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
