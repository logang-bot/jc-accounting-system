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
                                    @role('Administrator')
                                        Panel de Control
                                    @else
                                        Lista de Empresas
                                    @endrole
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

                                    @role('Administrator')
                                        <!-- Button: Crear Usuario -->
                                        <button type="button"
                                            class="bg-white/20 hover:bg-green-600/60 text-white font-semibold py-2 px-4 rounded w-full flex flex-row items-center gap-2"
                                            aria-controls="create-user-modal" data-hs-overlay="#create-user-modal">
                                            <x-carbon-user-follow class="w-5 h-5 " />
                                            Crear Usuario
                                        </button>
                                    @endrole

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



                                @role('Administrator')
                                    <div class="flex flex-col">
                                        <nav class="relative z-0 flex border border-gray-200 rounded-xl overflow-hidden dark:border-neutral-700"
                                            aria-label="Tabs" role="tablist" aria-orientation="horizontal">
                                            <button type="button"
                                                class="hs-tab-active:border-b-white hs-tab-active:text-gray-900 dark:hs-tab-active:text-white relative dark:hs-tab-active:border-b-white min-w-0 flex-1  first:border-s-0 border-s border-b-2 border-gray-200 py-4 px-4 text-gray-700 hover:text-white text-sm font-medium text-center overflow-hidden hover:bg-gray-50/50 focus:z-10 focus:outline-hidden focus:text-white disabled:opacity-50 disabled:pointer-events-none  dark:border-l-neutral-700 dark:border-b-gray-500  dark:text-gray-500 dark:hover:bg-neutral-700/50 dark:hover:text-white active"
                                                id="bar-with-underline-item-1" aria-selected="true"
                                                data-hs-tab="#bar-with-underline-1" aria-controls="bar-with-underline-1"
                                                role="tab">
                                                Empresas
                                            </button>
                                            <button type="button"
                                                class="hs-tab-active:border-b-white hs-tab-active:text-gray-900 dark:hs-tab-active:text-white relative dark:hs-tab-active:border-b-white min-w-0 flex-1 first:border-s-0 border-s border-b-2 border-gray-200 py-4 px-4 text-white hover:text-gray-700 text-sm font-medium text-center overflow-hidden hover:bg-gray-50/50focus:z-10 focus:outline-hidden focus:text-white disabled:opacity-50 disabled:pointer-events-none dark:border-l-neutral-700 dark:border-b-gray-500  dark:text-gray-500  dark:hover:bg-neutral-700/50 dark:hover:text-white"
                                                id="bar-with-underline-item-2" aria-selected="false"
                                                data-hs-tab="#bar-with-underline-2" aria-controls="bar-with-underline-2"
                                                role="tab">
                                                Usuarios
                                            </button>
                                        </nav>

                                        <div class="mt-3">
                                            <div id="bar-with-underline-1" role="tabpanel"
                                                aria-labelledby="bar-with-underline-item-1">
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
                                                                <tr class="odd:bg-white even:bg-gray-100/25 hover:bg-gray-100 dark:odd:bg-transparent dark:even:bg-neutral-600/25 dark:hover:bg-neutral-700/25"
                                                                    data-empresa-id="{{ $empresa->id }}">
                                                                    <td
                                                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200 flex flex-row items-center gap-1">
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
                                                                    <td
                                                                        class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                                                        @if ($empresa->activa)
                                                                            <a href="{{ route('show.empresas.home', $empresa->id) }}"
                                                                                class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-blue-600 hover:text-blue-800 focus:outline-hidden focus:text-blue-800 disabled:opacity-50 disabled:pointer-events-none dark:text-yellow-500 dark:hover:text-blue-400 dark:focus:text-blue-400">Ingresar</a>
                                                                        @endif
                                                                        @role('Administrator')
                                                                            <button type="button"
                                                                                data-empresa-id="{{ $empresa->id }}"
                                                                                class="archive-btn inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-blue-600 hover:text-blue-800 focus:outline-hidden focus:text-blue-800 disabled:opacity-50 disabled:pointer-events-none dark:text-gray-500 dark:hover:text-blue-400 dark:focus:text-blue-400 cursor-pointer">{{ $empresa->activa ? 'Archivar' : 'Activar' }}</button>
                                                                            <form id="archive-form-{{ $empresa->id }}"
                                                                                action="{{ route('empresas.archive', $empresa->id) }}"
                                                                                method="POST" class="hidden">
                                                                                @csrf
                                                                                @method('POST')
                                                                            </form>

                                                                            <button type="button"
                                                                                data-empresa-id="{{ $empresa->id }}"
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
                                            </div>
                                            <div id="bar-with-underline-2" class="hidden" role="tabpanel"
                                                aria-labelledby="bar-with-underline-item-2">
                                                <div class="max-w-3xl mx-auto">
                                                    <table class="min-w-150 divide-y divide-gray-200 dark:divide-neutral-500">
                                                        <thead class="text-white">
                                                            <tr>
                                                                <th
                                                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-white">
                                                                    Nombre</th>
                                                                <th
                                                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-white">
                                                                    Email</th>
                                                                <th
                                                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-white">
                                                                    Role(s)</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="text-white">
                                                            @foreach ($users as $user)
                                                                <tr
                                                                    class="odd:bg-white even:bg-gray-100/25 hover:bg-gray-100 dark:odd:bg-transparent dark:even:bg-neutral-600/25 dark:hover:bg-neutral-700/25">
                                                                    <td class="px-3 py-2">{{ $user->name }}</td>
                                                                    <td class="px-3 py-2">{{ $user->email }}</td>
                                                                    <td class="px-3 py-2">
                                                                        {{ $user->getRoleNames()->implode(', ') }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>

                                                    <div class="mt-3">
                                                        {{ $users->links() }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
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
                                                    <tr class="odd:bg-white even:bg-gray-100/25 hover:bg-gray-100 dark:odd:bg-transparent dark:even:bg-neutral-600/25 dark:hover:bg-neutral-700/25"
                                                        data-empresa-id="{{ $empresa->id }}">
                                                        <td
                                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200 flex flex-row items-center gap-1">
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
                                                                <a href="{{ route('show.empresas.home', $empresa->id) }}"
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
                                @endrole
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

                            {{-- Nombre --}}
                            <div class="mb-4">
                                <label for="empresaName"
                                    class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
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
        </x-modal>

        <x-modal id="create-user-modal">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-4">
                    <div class="flex items-center justify-between px-6 py-4 border-b">
                        <h5 class="text-lg font-semibold">
                            Crear Usuario
                        </h5>
                        <button class="text-gray-500 hover:text-gray-700 text-xl font-bold"
                            data-hs-overlay="#create-user-modal">&times;</button>
                    </div>

                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="block">Nombre</label>
                            <input type="text" name="name" class="border rounded w-full p-2" required>
                        </div>

                        <div class="mb-3">
                            <label class="block">Email</label>
                            <input type="email" name="email" class="border rounded w-full p-2" required>
                        </div>

                        <div class="mb-3">
                            <label class="block">Contrasenia</label>
                            <input type="password" name="password" class="border rounded w-full p-2" required>
                        </div>

                        <div class="mb-3">
                            <label class="block">Confirmar Contrasenia</label>
                            <input type="password" name="password_confirmation" class="border rounded w-full p-2"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="block">Role</label>
                            <select name="role" class="border rounded w-full p-2" required>
                                @foreach ($roles as $role)
                                    <option value="{{ $role }}">{{ $role }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Crear</button>
                    </form>
                </div>
            </div>
        </x-modal>

    </div>
@endsection
