<div class="header @@classList">
    <!-- navbar -->
    <nav class="navbar-classic navbar navbar-expand-lg">
        <a id="nav-toggle" href="#"><i data-feather="menu" class="nav-icon me-2 icon-xs"></i></a>
        <!-- Navbar nav -->
        <ul class="navbar-nav navbar-right-wrap ms-auto d-flex nav-top-wrap">
            <!-- List -->
            <li class="dropdown ms-2">
                <a class="rounded-circle" href="#" role="button" id="dropdownUser" data-bs-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <div class="avatar avatar-md avatar-indicators avatar-online">
                        @if (Auth::check())
                            <img alt="avatar" src="{{ Avatar::create(Auth::user()->name)->toBase64() }}" class="rounded-circle" />
                        @else
                            <img alt="avatar" src="{{ Avatar::create('Invitado')->toBase64() }}" class="rounded-circle" />
                        @endif
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUser">
                    <div class="px-4 pb-0 pt-2">
                        <div class="lh-1">
                            @if (Auth::check())
                                <h5 class="mb-1">{{ Auth::user()->name }}</h5>
                                <a href="#" class="text-inherit fs-6">{{ Auth::user()->email }}</a>
                            @else
                                <h5 class="mb-1">Invitado</h5>
                                <a href="#" class="text-inherit fs-6">Sin correo</a>
                            @endif
                        </div>
                        <div class="dropdown-divider mt-3 mb-2"></div>
                    </div>
                    <ul class="list-unstyled">
                        @if (Auth::check())
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="me-2 icon-xxs dropdown-item-icon" data-feather="user"></i>Edit Profile
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="me-2 icon-xxs dropdown-item-icon" data-feather="settings"></i>Account Settings
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('salir.empresa') }}"
                                    onclick="event.preventDefault(); document.getElementById('salir-empresa-form').submit();">
                                    <i class="me-2 icon-xxs dropdown-item-icon" data-feather="log-out"></i> Salir de Empresa
                                </a>
                                <form id="salir-empresa-form" action="{{ route('salir.empresa') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="me-2 icon-xxs dropdown-item-icon" data-feather="power"></i>{{ __('Logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        @else
                            <li>
                                <a class="dropdown-item" href="{{ route('login') }}">
                                    <i class="me-2 icon-xxs dropdown-item-icon" data-feather="log-in"></i>Iniciar sesión
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </li>
        </ul>
    </nav>
</div>
