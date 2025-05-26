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

                <li class="hs-accordion" id="users-accordion">
                    <button type="button"
                        class="hs-accordion-toggle w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200"
                        aria-expanded="true" aria-controls="users-accordion-collapse-1">
                        <x-carbon-gui-management class="w-4 h-4" />
                        Gestion de empresa
                        <x-carbon-chevron-down class="w-4 h-4 ms-auto" />
                    </button>

                    <div id="users-accordion-collapse-1"
                        class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden"
                        role="region" aria-labelledby="users-accordion">
                        <ul class="hs-accordion-group pt-1 ps-7 space-y-1" data-hs-accordion-always-open>
                            <li class="hs-accordion" id="users-accordion-sub-1">
                                <button type="button"
                                    class="hs-accordion-toggle w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200"
                                    aria-expanded="true" aria-controls="users-accordion-sub-1-collapse-1">
                                    <a href="{{ route('show.empresas.home', ['id' => session('empresa_id', Auth::user()->empresa_id ?? 1)]) }}"
                                        class="hover:text-gray-300 flex flex-row items-center">
                                        Datos de la Empresa
                                    </a>
                                </button>
                            </li>
                            <li class="hs-accordion" id="users-accordion-sub-1">
                                <button type="button"
                                    class="hs-accordion-toggle w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200"
                                    aria-expanded="true" aria-controls="users-accordion-sub-1-collapse-1">
                                    <a href="{{ route('show.empresas.create') }}"
                                        class="hover:text-gray-300 flex flex-row items-center">
                                        Salir de la Empresa
                                    </a>
                                </button>
                            </li>
                        </ul>
                    </div>
                </li>

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
                            <li>
                                <a href="{{ route('show.cuentas.home') }}"
                                    class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200">
                                    Plan de Cuentas</a>
                            </li>
                            <li>
                                <a href="{{ route('show.comprobantes.home') }}"
                                    class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200">
                                    Comprobantes</a>
                            </li>
                            <li>
                                <a href="#"
                                    class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200">
                                    Libro de Compras</a>
                            </li>
                            <li>
                                <a href="#"
                                    class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200">
                                    Libro de Ventas</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="hs-accordion" id="projects-accordion">
                    <button type="button"
                        class="hs-accordion-toggle w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200"
                        aria-expanded="true" aria-controls="projects-accordion-sub-1-collapse-1">
                        <x-carbon-locked class="w-4 h-4" />
                        Cuenta
                        <x-carbon-chevron-down class="w-4 h-4 ms-auto" />
                    </button>

                    <div id="projects-accordion-sub-1-collapse-1"
                        class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden"
                        role="region" aria-labelledby="projects-accordion">
                        <ul class="pt-1 ps-7 space-y-1">
                            <li>
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                    class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200 hover:text-gray-300">
                                    Cerrar Sesion</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

</div>
