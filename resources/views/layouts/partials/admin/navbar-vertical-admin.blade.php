<!-- Sidebar -->
<nav class="navbar-vertical navbar">
    <div class="nav-scroller">
        <!-- Brand logo -->
        <a class="navbar-brand">
            <div style="text-align: center;">
                <h3 style="color: white;"> AUDITORIA </h3>
                <h4 style="color: white;"> CONTADURIA PÚBLICA </h4>
            </div>
        </a>
        <!-- Navbar nav -->
        <ul class="navbar-nav flex-column" id="sideNavbar">
            <li class="nav-item">
                <a class="nav-link has-arrow" href="{{ route('home') }}">
                    <i data-feather="home" class="nav-icon icon-xs me-2"></i>Admin Dashboard
                </a>
            </li>            
            <!-- Nav item -->
            <li class="nav-item">
                <div class="navbar-heading">Front Section</div>
            </li>
            <!-- Nav item -->
            <li class="nav-item">
                <a class="nav-link has-arrow" href="#!" data-bs-toggle="collapse" data-bs-target="#navPages3"
                    aria-expanded="false" aria-controls="navPages3">
                    <i data-feather="layers" class="nav-icon icon-xs me-2"></i> GESTIONAR
                </a>
                <div id="navPages3" class="collapse" data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('empresa.show', ['id' => session('empresa_id', Auth::user()->empresa_id ?? 1)]) }}">
                                Datos de la Empresa
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <!-- Nav item -->
            <li class="nav-item">
                <a class="nav-link has-arrow" href="#!" data-bs-toggle="collapse" data-bs-target="#navPages4"
                    aria-expanded="false" aria-controls="navPages4">
                    <i data-feather="layers" class="nav-icon icon-xs me-2"></i> CONTABILIDAD
                </a>
                <div id="navPages4" class="collapse" data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        <li class="nav-item"> <a class="nav-link" href="{{ route('cuentas.index') }}">Plan de Cuentas</a> </li>
                        <li class="nav-item"> <a class="nav-link" href="{{ route('comprobantes.index') }}">Comprobantes</a> </li>
                        <li class="nav-item"> <a class="nav-link" href="#">Libro de Compras</a> </li>
                        <li class="nav-item"> <a class="nav-link" href="#">Libro de Ventas</a> </li>
                    </ul>
                </div>
            </li>
            <!-- Nav item -->
            <li class="nav-item">
                <a class="nav-link has-arrow" href="#!" data-bs-toggle="collapse"
                    data-bs-target="#navAuthentication" aria-expanded="false" aria-controls="navAuthentication">
                    <i data-feather="lock" class="nav-icon icon-xs me-2"></i> Authentication
                </a>
                <div id="navAuthentication" class="collapse" data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="pages/sign-in.html"> Sign In</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="pages/sign-up.html"> Sign Up</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="pages/forget-password.html">
                                Forget Password
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</nav>
