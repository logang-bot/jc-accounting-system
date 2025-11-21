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

    <div class="max-w-7xl mx-auto p-6 m-6 bg-white shadow-md rounded-xl">
        <h2 class="text-2xl font-semibold mb-6">
            Crear empresa
        </h2>

        <!-- Modal Body -->
        <div class="p-6">
            <form id="crearEmpresaForm" method="POST" action="{{ route('empresas.store') }}">
                @csrf

                {{-- Nombre --}}
                <div class="mb-4">
                    <label for="empresaName" class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                    <input id="empresaName" name="nombre" type="text" required
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:ring-2 focus:outline-none" />
                </div>

                {{-- Tipo documento --}}
                <div class="mb-4">
                    <label for="tipo_documento">Tipo de documento</label>
                    <select name="tipo_documento" id="tipo_documento" required
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:ring-2 focus:outline-none">
                        <option value="CI">CI</option>
                        <option value="NIT">NIT</option>
                    </select>
                </div>

                {{-- NIT / CI --}}
                <div class="mb-4">
                    <label for="documento">Número</label>
                    <input type="text" name="numero_documento" id="documento" required
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:ring-2 focus:outline-none">
                </div>

                {{-- Casa Matriz --}}
                <div class="mb-4 flex items-center">
                    <input type="hidden" name="casa_matriz" value="0">
                    <input id="casa_matriz" name="casa_matriz" type="checkbox" value="1"
                        class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                        onchange="toggleSucursal()">
                    <label for="casa_matriz" class="ml-2 block text-sm text-gray-700">¿Es casa matriz?</label>
                </div>

                <div class="mb-4" id="sucursalContainer">
                    <label for="sucursal" class="block text-sm font-medium text-gray-700 mb-1">Nombre de sucursal</label>
                    <input type="text" name="sucursal" id="sucursal"
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:ring-2 focus:outline-none">
                </div>

                {{-- Fecha Inicio --}}
                <div class="mb-4">
                    <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Inicio</label>
                    <input id="fecha_inicio" name="fecha_inicio" type="date" required
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:ring-2 focus:outline-none" />
                </div>

                {{-- Fecha Fin --}}
                <div class="mb-4">
                    <label for="fecha_fin" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Fin</label>
                    <input id="fecha_fin" name="fecha_fin" type="date"
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:ring-2 focus:outline-none" />
                </div>

                {{-- tipo de empresa --}}
                <div class="mb-6">
                    <label for="tipo_empresa" class="block text-sm font-medium text-gray-700 mb-1">Tipo de empresa</label>
                    <select id="tipo_empresa" name="tipo_empresa" required
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:ring-2 focus:outline-none">
                        <option value="Mineria">Mineria</option>
                        <option value="Comercial">Comercial</option>
                        <option value="Agropecuaria">Agropecuaria</option>
                        <option value="Industrial">Industrial</option>

                    </select>
                </div>

                {{-- Botón de Envío --}}
                <button type="submit"
                    class="w-full bg-[var(--header-bg)] hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded cursor-pointer">
                    Crear Empresa
                </button>
            </form>
        </div>
    </div>

    <script>
        function toggleSucursal() {
            const checkbox = document.getElementById('casa_matriz');
            document.getElementById('sucursalContainer').style.display = checkbox.checked ? 'none' : 'block';
        }
        window.addEventListener('load', toggleSucursal);
    </script>
@endsection
