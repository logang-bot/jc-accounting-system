<!-- Sidebar -->
{{-- <div id="hs-sidebar-content-push"
    class="hs-overlay [--auto-close:lg] lg:block lg:translate-x-0 lg:end-auto lg:bottom-0 w-64
hs-overlay-open:translate-x-0
-translate-x-full transition-all duration-300 transform
h-full
hidden
fixed top-0 start-0 bottom-0 z-60
bg-white border-e border-gray-200 dark:bg-neutral-800 dark:border-neutral-700"
    role="dialog" tabindex="-1" aria-label="Sidebar">
    <div class="relative flex flex-col h-full max-h-full ">
        <!-- Header -->
        <header class="p-4 flex justify-between items-center gap-x-2">
            <a class="flex-none font-semibold text-xl focus:outline-hidden focus:opacity-80 dark:text-white"
                href="#" aria-label="Brand">Brand</a>

            <div class="lg:hidden -me-2">
                <!-- Close Button -->
                <button type="button"
                    class="flex justify-center items-center gap-x-3 size-6 bg-white border border-gray-200 text-sm text-gray-600 hover:bg-gray-100 rounded-full disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:hover:text-neutral-200 dark:focus:text-neutral-200"
                    data-hs-overlay="#hs-sidebar-content-push">
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M18 6 6 18" />
                        <path d="m6 6 12 12" />
                    </svg>
                    <span class="sr-only">Close</span>
                </button>
                <!-- End Close Button -->
            </div>
        </header>
        <!-- End Header -->

        <!-- Body -->
        <nav
            class="h-full overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500">
            <div class="hs-accordion-group pb-0 px-2  w-full flex flex-col flex-wrap" data-hs-accordion-always-open>
                <ul class="space-y-1">
                    <li>
                        <a class="flex items-center gap-x-3.5 py-2 px-2.5 bg-gray-100 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-700 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-white"
                            href="#">
                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                <polyline points="9 22 9 12 15 12 15 22" />
                            </svg>
                            Dashboard
                        </a>
                    </li>

                    <li class="hs-accordion" id="users-accordion">
                        <button type="button"
                            class="hs-accordion-toggle w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200"
                            aria-expanded="true" aria-controls="users-accordion-collapse-1">
                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                <circle cx="9" cy="7" r="4" />
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                            </svg>
                            Users

                            <svg class="hs-accordion-active:block ms-auto hidden size-4 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="m18 15-6-6-6 6" />
                            </svg>

                            <svg class="hs-accordion-active:hidden ms-auto block size-4 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </button>

                        <div id="users-accordion-collapse-1"
                            class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden"
                            role="region" aria-labelledby="users-accordion">
                            <ul class="hs-accordion-group pt-1 ps-7 space-y-1" data-hs-accordion-always-open>
                                <li class="hs-accordion" id="users-accordion-sub-1">
                                    <button type="button"
                                        class="hs-accordion-toggle w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200"
                                        aria-expanded="true" aria-controls="users-accordion-sub-1-collapse-1">
                                        Sub Menu 1

                                        <svg class="hs-accordion-active:block ms-auto hidden size-4 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path d="m18 15-6-6-6 6" />
                                        </svg>

                                        <svg class="hs-accordion-active:hidden ms-auto block size-4 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="m6 9 6 6 6-6" />
                                        </svg>
                                    </button>

                                    <div id="users-accordion-sub-1-collapse-1"
                                        class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden"
                                        role="region" aria-labelledby="users-accordion-sub-1">
                                        <ul class="pt-1 ps-2 space-y-1">
                                            <li>
                                                <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200"
                                                    href="#">
                                                    Link 1
                                                </a>
                                            </li>
                                            <li>
                                                <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200"
                                                    href="#">
                                                    Link 2
                                                </a>
                                            </li>
                                            <li>
                                                <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200"
                                                    href="#">
                                                    Link 3
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>

                                <li class="hs-accordion" id="users-accordion-sub-2">
                                    <button type="button"
                                        class="hs-accordion-toggle w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200"
                                        aria-expanded="true" aria-controls="users-accordion-sub-2-collapse-1">
                                        Sub Menu 2

                                        <svg class="hs-accordion-active:block ms-auto hidden size-4 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="m18 15-6-6-6 6" />
                                        </svg>

                                        <svg class="hs-accordion-active:hidden ms-auto block size-4 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="m6 9 6 6 6-6" />
                                        </svg>
                                    </button>

                                    <div id="users-accordion-sub-2-collapse-1"
                                        class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden"
                                        role="region" aria-labelledby="users-accordion-sub-2">
                                        <ul class="pt-1 ps-2 space-y-1">
                                            <li>
                                                <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200"
                                                    href="#">
                                                    Link 1
                                                </a>
                                            </li>
                                            <li>
                                                <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200"
                                                    href="#">
                                                    Link 2
                                                </a>
                                            </li>
                                            <li>
                                                <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200"
                                                    href="#">
                                                    Link 3
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="hs-accordion" id="account-accordion">
                        <button type="button"
                            class="hs-accordion-toggle w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200"
                            aria-expanded="true" aria-controls="account-accordion-sub-1-collapse-1">
                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="18" cy="15" r="3" />
                                <circle cx="9" cy="7" r="4" />
                                <path d="M10 15H6a4 4 0 0 0-4 4v2" />
                                <path d="m21.7 16.4-.9-.3" />
                                <path d="m15.2 13.9-.9-.3" />
                                <path d="m16.6 18.7.3-.9" />
                                <path d="m19.1 12.2.3-.9" />
                                <path d="m19.6 18.7-.4-1" />
                                <path d="m16.8 12.3-.4-1" />
                                <path d="m14.3 16.6 1-.4" />
                                <path d="m20.7 13.8 1-.4" />
                            </svg>
                            Account

                            <svg class="hs-accordion-active:block ms-auto hidden size-4 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="m18 15-6-6-6 6" />
                            </svg>

                            <svg class="hs-accordion-active:hidden ms-auto block size-4 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </button>

                        <div id="account-accordion-sub-1-collapse-1"
                            class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden"
                            role="region" aria-labelledby="account-accordion">
                            <ul class="pt-1 ps-7 space-y-1">
                                <li>
                                    <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200"
                                        href="#">
                                        Link 1
                                    </a>
                                </li>
                                <li>
                                    <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200"
                                        href="#">
                                        Link 2
                                    </a>
                                </li>
                                <li>
                                    <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200"
                                        href="#">
                                        Link 3
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="hs-accordion" id="projects-accordion">
                        <button type="button"
                            class="hs-accordion-toggle w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200"
                            aria-expanded="true" aria-controls="projects-accordion-sub-1-collapse-1">
                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M15.5 2H8.6c-.4 0-.8.2-1.1.5-.3.3-.5.7-.5 1.1v12.8c0 .4.2.8.5 1.1.3.3.7.5 1.1.5h9.8c.4 0 .8-.2 1.1-.5.3-.3.5-.7.5-1.1V6.5L15.5 2z" />
                                <path d="M3 7.6v12.8c0 .4.2.8.5 1.1.3.3.7.5 1.1.5h9.8" />
                                <path d="M15 2v5h5" />
                            </svg>
                            Projects

                            <svg class="hs-accordion-active:block ms-auto hidden size-4 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="m18 15-6-6-6 6" />
                            </svg>

                            <svg class="hs-accordion-active:hidden ms-auto block size-4 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </button>

                        <div id="projects-accordion-sub-1-collapse-1"
                            class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden"
                            role="region" aria-labelledby="projects-accordion">
                            <ul class="pt-1 ps-7 space-y-1">
                                <li>
                                    <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200"
                                        href="#">
                                        Link 1
                                    </a>
                                </li>
                                <li>
                                    <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200"
                                        href="#">
                                        Link 2
                                    </a>
                                </li>
                                <li>
                                    <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200"
                                        href="#">
                                        Link 3
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li>
                        <a class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200"
                            href="#">
                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
                                <line x1="16" x2="16" y1="2" y2="6" />
                                <line x1="8" x2="8" y1="2" y2="6" />
                                <line x1="3" x2="21" y1="10" y2="10" />
                                <path d="M8 14h.01" />
                                <path d="M12 14h.01" />
                                <path d="M16 14h.01" />
                                <path d="M8 18h.01" />
                                <path d="M12 18h.01" />
                                <path d="M16 18h.01" />
                            </svg>
                            Calendar <span
                                class="ms-auto py-0.5 px-1.5 inline-flex items-center gap-x-1.5 text-xs bg-gray-200 text-gray-800 rounded-full dark:bg-neutral-600 dark:text-neutral-200">New</span>
                        </a>
                    </li>
                    <li>
                        <a class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200"
                            href="#">
                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" />
                                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
                            </svg>
                            Documentation
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- End Body -->
    </div>
</div>
<!-- End Sidebar --> --}}

























<!-- Sidebar -->
<div :class="sidebarOpen ? 'w-64' : 'w-0'" class="transition-all duration-200">
    <nav
        class="navbar-vertical navbar transition-all duration-300 overflow-hidde bg-cyan-950 text-white w-64 h-screen overflow-y-auto">
        <div class="p-4">
            <!-- Brand logo -->
            <div class="text-center mb-6">
                <h3 class="text-xl font-bold">AUDITORIA</h3>
                <h4 class="text-sm font-medium">CONTADURIA PÚBLICA</h4>
            </div>

            <!-- Navigation -->
            <ul class="space-y-2 text-white/60" id="sideNavbar">
                <!-- Home -->
                <li>
                    <a href="{{ route('home') }}" class="flex items-center px-4 py-2 hover:bg-gray-700 rounded">
                        <x-carbon-home data-feather="home" class="w-4 h-4 mr-2" />
                        Inicio
                    </a>
                </li>

                <!-- Section heading -->
                <li>
                    <div class="text-xs uppercase px-4 pt-4 pb-1 text-white/30 textspace tracking-widest">Gestion de
                        cuentas
                    </div>
                </li>

                <!-- Gestionar -->
                <li x-data="{ subItemsOpen: false }">
                    <button type="button"
                        class="w-full flex items-center px-4 py-2 hover:bg-gray-700 rounded justify-between"
                        @click.prevent="subItemsOpen = !subItemsOpen" data-collapse-toggle="navPages3">
                        <span class="flex items-center">
                            <x-carbon-gui-management class="w-4 h-4 mr-2" /> Gestionar
                        </span>
                        <x-carbon-chevron-down class="w-6 h-6 mr-2" />
                    </button>
                    <ul id="navPages3" class="pl-6 mb-7 mt-3" :class="subItemsOpen ? '' : 'hidden'" class="bg-red-500">
                        <li>
                            <a href="{{ route('show.empresas.home', ['id' => session('empresa_id', Auth::user()->empresa_id ?? 1)]) }}"
                                class="hover:text-gray-300 flex flex-row items-center">
                                <x-carbon-chevron-right class="w-6 h-6 mr-2" />Datos de la Empresa
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Contabilidad -->
                <li x-data="{ subItemsOpen: false }">
                    <button type ="button"
                        class="w-full flex items-center px-4 py-2 hover:bg-gray-700 rounded justify-between"
                        @click.prevent="subItemsOpen = !subItemsOpen" data-collapse-toggle="navPages4">
                        <span class="flex items-center">
                            <x-carbon-account class="w-4 h-4 mr-2" /> Contabilidad
                        </span>
                        <x-carbon-chevron-down class="w-6 h-6 mr-2" />
                    </button>
                    <ul id="navPages4" class="pl-6 mt-1 space-y-1" :class="subItemsOpen ? '' : 'hidden'">
                        <li><a href="{{ route('cuentas.index') }}"
                                class="px-2 py-1 hover:text-gray-300  flex flex-row items-center">
                                <x-carbon-chevron-right class="w-6 h-6 mr-2" />Plan de Cuentas</a></li>
                        <li><a href="{{ route('comprobantes.index') }}"
                                class="px-2 py-1 hover:text-gray-300  flex flex-row items-center">
                                <x-carbon-chevron-right class="w-6 h-6 mr-2" />Comprobantes</a></li>
                        <li><a href="#" class="px-2 py-1 hover:text-gray-300  flex flex-row items-center">
                                <x-carbon-chevron-right class="w-6 h-6 mr-2" />Libro de Compras</a></li>
                        <li><a href="#" class="px-2 py-1 hover:text-gray-300  flex flex-row items-center">
                                <x-carbon-chevron-right class="w-6 h-6 mr-2" />Libro de Ventas</a></li>
                    </ul>
                </li>

                <!-- Section heading -->
                <li>
                    <div class="text-xs uppercase px-4 pt-4 pb-1 text-white/30 textspace tracking-widest">Datos
                        personales
                    </div>
                </li>

                <!-- Authentication -->
                <li x-data="{ subItemsOpen: false }">
                    <button type ="button"
                        class="w-full flex items-center px-4 py-2 hover:bg-gray-700 rounded justify-between"
                        @click.prevent="subItemsOpen = !subItemsOpen" data-collapse-toggle="navAuthentication">
                        <span class="flex items-center">
                            <x-carbon-locked class="w-4 h-4 mr-2" /> Cuenta
                        </span>
                        <x-carbon-chevron-down class="w-6 h-6 mr-2" />
                    </button>
                    <ul id="navAuthentication" class="pl-6 mt-1 space-y-1" :class="subItemsOpen ? '' : 'hidden'">
                        <li><a href="pages/sign-in.html" class="block px-2 py-1 hover:text-gray-300">Sign In</a></li>
                        <li><a href="pages/sign-up.html" class="block px-2 py-1 hover:text-gray-300">Sign Up</a></li>
                        <li><a href="pages/forget-password.html" class="block px-2 py-1 hover:text-gray-300">Forget
                                Password</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</div>
