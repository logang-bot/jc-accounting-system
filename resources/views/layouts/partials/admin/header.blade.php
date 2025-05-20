<div id="page-content" class="bg-white shadow px-4 py-3 flex items-center justify-between transition-all duration-200">
    <!-- Navbar toggle -->
    <a id="nav-toggle" href="#" @click.prevent="sidebarOpen = !sidebarOpen" class="text-gray-700 hover:text-gray-900">
        <x-carbon-menu class="w-5 h-5" />
    </a>

    <!-- Right side user dropdown -->
    <div class="hs-dropdown inline-flex relative">
        <button id="hs-dropdown-default"
            class="hs-dropdown-toggle py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-2xs hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
            aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
            @if (Auth::check())
                Welcome {{ Auth::user()->name }}
            @endif
            <div class="w-9 h-9 rounded-full border-2 border-white overflow-hidden relative ml-5">
                @if (Auth::check())
                    <img alt="avatar" src="{{ Avatar::create(Auth::user()->name)->toBase64() }}"
                        class="w-full h-full object-cover" />
                @else
                    <img alt="avatar" src="{{ Avatar::create('Invitado')->toBase64() }}"
                        class="w-full h-full object-cover" />
                @endif
            </div>
        </button>

        <!-- Dropdown -->
        <div id="userDropdown"
            class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-60 bg-white shadow-md rounded-lg mt-2 dark:bg-neutral-800 dark:border dark:border-neutral-700 dark:divide-neutral-700 after:h-4 after:absolute after:-bottom-4 after:start-0 after:w-full before:h-4 before:absolute before:-top-4 before:start-0 before:w-full"
            role="menu" aria-orientation="vertical" aria-labelledby="hs-dropdown-default">
            {{-- <div class="px-4 py-3 border-b">
                @if (Auth::check())
                    <h5 class="text-sm font-semibold">{{ Auth::user()->name }}</h5>
                    <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                @else
                    <h5 class="text-sm font-semibold">Invitado</h5>
                    <p class="text-xs text-gray-500">Sin correo</p>
                @endif
            </div> --}}
            <ul class="py-2 text-sm text-gray-700">
                @if (Auth::check())
                    <li>
                        <a href="#" class="block px-4 py-2 hover:bg-gray-100">
                            <i data-feather="user" class="inline w-4 h-4 mr-2"></i> Edit Profile
                        </a>
                    </li>
                    <li>
                        <a href="#" class="block px-4 py-2 hover:bg-gray-100">
                            <i data-feather="settings" class="inline w-4 h-4 mr-2"></i> Account Settings
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('empresas.exit') }}"
                            onclick="event.preventDefault(); document.getElementById('salir-empresa-form').submit();"
                            class="block px-4 py-2 hover:bg-gray-100">
                            <i data-feather="log-out" class="inline w-4 h-4 mr-2"></i> Salir de Empresa
                        </a>
                        <form id="salir-empresa-form" action="{{ route('empresas.exit') }}" method="POST"
                            class="hidden">
                            @csrf
                        </form>
                    </li>
                    <li>
                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="block px-4 py-2 hover:bg-gray-100">
                            <i data-feather="power" class="inline w-4 h-4 mr-2"></i> {{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    </li>
                    {{-- @else
                    <li>
                        <a href="{{ route('login') }}" class="block px-4 py-2 hover:bg-gray-100">
                            <i data-feather="log-in" class="inline w-4 h-4 mr-2"></i> Iniciar sesión
                        </a>
                    </li> --}}
                @endif
            </ul>
        </div>
    </div>
</div>
