<div id="page-content" class="bg-white shadow px-4 py-3 flex items-center justify-between transition-all duration-200">
    <!-- Navbar toggle -->
    <a id="nav-toggle" href="#" @click.prevent="sidebarOpen = !sidebarOpen" class="text-gray-700 hover:text-gray-900">
        <x-carbon-menu class="w-5 h-5" />
    </a>

    <!-- Right side user dropdown -->
    <div class="relative">
        <button id="userMenuButton" class="flex items-center focus:outline-none" data-dropdown-toggle="userDropdown">
            <div class="w-9 h-9 rounded-full border-2 border-white overflow-hidden relative">
                @if (Auth::check())
                    <img alt="avatar" src="{{ Avatar::create(Auth::user()->name)->toBase64() }}"
                        class="w-full h-full object-cover" />
                @else
                    <img alt="avatar" src="{{ Avatar::create('Invitado')->toBase64() }}"
                        class="w-full h-full object-cover" />
                @endif
                <span
                    class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 border-2 border-white rounded-full"></span>
            </div>
        </button>

        <!-- Dropdown -->
        <div id="userDropdown" class="hidden absolute right-0 mt-2 w-64 bg-white shadow-lg rounded-lg z-50">
            <div class="px-4 py-3 border-b">
                @if (Auth::check())
                    <h5 class="text-sm font-semibold">{{ Auth::user()->name }}</h5>
                    <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                @else
                    <h5 class="text-sm font-semibold">Invitado</h5>
                    <p class="text-xs text-gray-500">Sin correo</p>
                @endif
            </div>
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
                @else
                    <li>
                        <a href="{{ route('login') }}" class="block px-4 py-2 hover:bg-gray-100">
                            <i data-feather="log-in" class="inline w-4 h-4 mr-2"></i> Iniciar sesión
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>
