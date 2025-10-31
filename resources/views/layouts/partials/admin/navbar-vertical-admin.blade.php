<!-- Sidebar -->
<div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed top-0 start-0 bottom-0 z-60 transition-all duration-200 bg-white border-e border-gray-200 dark:bg-neutral-800 dark:border-neutral-700 min-w-64">

    <header class="p-3 flex items-center gap-x-4">
        <img src="{{ asset('assets/images/img_escudo.png') }}" class="w-16 h-16 grayscale" />
        <div class="text-white">
            <h3 class="text-xl font-bold">AUDITORIA</h3>
            <h4 class="text-sm font-medium">CONTADURIA PÚBLICA</h4>
        </div>
    </header>
    <!-- End Header -->

    <!-- Body -->
    <nav
        class="h-full overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500">
        <div class="hs-accordion-group pb-0 px-2  w-full flex flex-col flex-wrap" data-hs-accordion-always-open>
            <ul class="space-y-1">
                <li>
                    <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-white"
                        href="#">
                        <x-carbon-home data-feather="home" class="w-4 h-4" />
                        Inicio
                    </a>
                </li>

                <li class="hs-accordion" id="empresa-accordion">
                    <button type="button"
                        class="hs-accordion-toggle w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200"
                        aria-expanded="true" aria-controls="empresa-accordion-collapse-1">
                        <x-carbon-gui-management class="w-4 h-4" />
                        Gestion de empresa
                        <x-carbon-chevron-down class="w-4 h-4 ms-auto" />
                    </button>

                    <div id="empresa-accordion-collapse-1"
                        class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden"
                        role="region" aria-labelledby="empresa-accordion">
                        <ul class="hs-accordion-group pt-1 ps-7 space-y-1" data-hs-accordion-always-open>
                            <li class="hs-accordion" id="empresa-accordion-sub-1">
                                <button type="button"
                                    class="hs-accordion-toggle w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200"
                                    aria-expanded="true" aria-controls="empresa-accordion-sub-1-collapse-1">
                                    <a href="{{ route('show.empresas.home') }}"
                                        class="hover:text-gray-300 flex flex-row items-center">
                                        Lista de empresas
                                    </a>
                                </button>
                            </li>
                            @if (session()->has('empresa_id'))
                                <li class="hs-accordion" id="empresa-accordion-sub-1">
                                    <button type="button"
                                        class="hs-accordion-toggle w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200"
                                        aria-expanded="true" aria-controls="empresa-accordion-sub-1-collapse-1">
                                        <a href="{{ route('show.empresas.detail', ['id' => session('empresa_id', Auth::user()->empresa_id ?? 1)]) }}"
                                            class="hover:text-gray-300 flex flex-row items-center">
                                            Datos de la Empresa
                                        </a>
                                    </button>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>

                @hasanyrole('Administrator|Teacher')
                    <li class="hs-accordion" id="user-accordion">
                        <button type="button"
                            class="hs-accordion-toggle w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200"
                            aria-expanded="true" aria-controls="user-accordion-collapse-1">
                            <x-carbon-user-follow class="w-4 h-4 " />
                            Gestion de usuarios
                            <x-carbon-chevron-down class="w-4 h-4 ms-auto" />
                        </button>

                        <div id="user-accordion-collapse-1"
                            class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden"
                            role="region" aria-labelledby="user-accordion">
                            <ul class="hs-accordion-group pt-1 ps-7 space-y-1" data-hs-accordion-always-open>
                                <li class="hs-accordion" id="user-accordion-sub-1">
                                    <button type="button"
                                        class="hs-accordion-toggle w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200"
                                        aria-expanded="true" aria-controls="user-accordion-sub-1-collapse-1">
                                        <a href="{{ route('admin.show.usuarios.home') }}"
                                            class="hover:text-gray-300 flex flex-row items-center">
                                            Lista de usuarios
                                        </a>
                                    </button>
                                </li>
                                <li class="hs-accordion" id="user-accordion-sub-1">
                                    <button type="button"
                                        class="hs-accordion-toggle w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200"
                                        aria-expanded="true" aria-controls="user-accordion-sub-1-collapse-1">
                                        <a href="{{ route('admin.show.usuarios.create') }}"
                                            class="hover:text-gray-300 flex flex-row items-center">
                                            Crear usuario
                                        </a>
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endhasanyrole

                @if (session()->has('empresa_id'))
                    <li class="hs-accordion" id="account-accordion">
                        <button type="button"
                            class="hs-accordion-toggle w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200"
                            aria-expanded="true" aria-controls="account-accordion-sub-1-collapse-1">
                            <x-carbon-account class="w-4 h-4" />
                            Contabilidad
                            <x-carbon-chevron-down class="w-4 h-4 ms-auto" />
                        </button>

                        <div id="account-accordion-sub-1-collapse-1"
                            class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden"
                            role="region" aria-labelledby="account-accordion">

                            <ul class="pt-1 ps-7 space-y-1">
                                <!-- Plan de Cuentas -->
                                <li>
                                    <a href="{{ route('show.cuentas.home', ['id' => session('plan_id', 1)]) }}"
                                        class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200">
                                        Plan de Cuentas
                                    </a>
                                </li>
                                <!-- Comprobantes con submenú -->
                                <li class="hs-accordion" id="comprobantes-accordion">
                                    <button type="button"
                                        class="hs-accordion-toggle w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200"
                                        aria-expanded="false" aria-controls="comprobantes-accordion-collapse">
                                        Comprobantes
                                        <x-carbon-chevron-down class="w-4 h-4 ms-auto" />
                                    </button>
                                    <div id="comprobantes-accordion-collapse"
                                        class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden"
                                        role="region" aria-labelledby="comprobantes-accordion">
                                        <ul class="ps-7 space-y-1">
                                            <li>
                                                <a href="{{ route('show.comprobantes.home') }}"
                                                    class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200">
                                                    Ver Comprobantes</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('show.comprobantes.create') }}"
                                                    class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-blue-800 rounded-lg hover:bg-blue-100 focus:outline-hidden focus:bg-blue-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200">
                                                    Nuevo Comprobante</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <!-- Los demás ítems siguen igual -->
                                <li>
                                    <a href="{{ route('libro-diario.index') }}"
                                        class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200">
                                        Libro Diario</a>
                                </li>

                                <li>
                                    <a href="{{ route('show.libro-mayor.index') }}?moneda=bs&saldo=con_saldo"
                                        class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200">
                                        Libro Mayor</a>
                                </li>

                                <li>
                                    <a href="{{ route('estado-resultados.index') }}"
                                        class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200">
                                        Estado de Resultado</a>
                                </li>
                                <li>
                                    <a href="{{ route('balances.general') }}"
                                        class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200">
                                        Balance General</a>
                                </li>
                                <li>
                                    <a href="{{ route('show.tipo-cambio.index') }}"
                                        class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200">
                                        Historial de tipo de cambios</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif
            </ul>
        </div>
    </nav>

</div>
