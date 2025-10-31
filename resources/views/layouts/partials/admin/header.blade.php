@php
    use App\Models\Empresa;

    $empresa = null;
    if (session()->has('empresa_id')) {
        $empresa = Empresa::find(session('empresa_id'));
    }
@endphp

<div id="page-content" class="bg-white shadow px-4 py-3 flex items-center justify-between transition-all duration-200">
    <!-- Navbar toggle -->
    <a id="nav-toggle" href="#" @click.prevent="sidebarOpen = !sidebarOpen"
        class="text-gray-700 hover:text-gray-900 flex gap-4">
        <x-carbon-menu class="w-5 h-5" />
        @if ($empresa)
            <span class="text-sm text-gray-800">
                <strong>Estas operando en la empresa:</strong> {{ $empresa->name }}
            </span>
        @else
            <span class="text-sm text-gray-400 italic">
                No hay una empresa seleccionada
            </span>
        @endif
    </a>

    <!-- Right side user dropdown -->
    <div class="hs-dropdown [--auto-close:inside] relative inline-flex">
        <button id="hs-dropdown-default" type="button"
            class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-2xs hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none cursor-pointer">
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
        <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 hidden min-w-60 bg-white shadow-md rounded-lg mt-2  after:h-4 after:absolute after:-bottom-4 after:start-0 after:w-full before:h-4 before:absolute before:-top-4 before:start-0 before:w-full"
            role="menu" aria-orientation="vertical" aria-labelledby="hs-dropdown-default">

            <ul class="py-2 text-sm text-gray-700">
                @if (Auth::check())
                    <li>
                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 ">
                            Cerrar Sesion</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>
