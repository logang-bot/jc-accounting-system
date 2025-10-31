@extends('layouts.main')

@section('customstyles')
    @vite('resources/css/login.css')
@endsection

@section('content')
    <div class="flex flex-row">

        <div class="flex-2 welcome-section">
            <nav class="hero-nav hero-container">
                <img src="{{ asset('assets/images/img_escudo.png') }}" class="logo-icon" />
            </nav>
            <div
                class="absolute left-[40%] top-1/2 transform -translate-x-1/2 -translate-y-1/2 leading-tight max-w-[1200px]">
                <div>
                    <h1>Sistema contable de la UATF</h1>
                    <p>
                        Bienvenido al sistema contable de Universidad Autonoma Tomas Frias, si dispone de una cuenta ingrese
                        sus credenciales en la seccion de Ingresar, caso contrario, por favor contacte con el administrador
                        para crear una
                    </p>
                </div>
            </div>
        </div>
        <div class="flex-3 content">
            <form class="form" action="{{ route('login') }}" method="POST">
                @csrf
                <h2 class="text-center text-2xl font-semibold text-gray-800">Iniciar sesion</h2>
                <div class="flex-column">
                    <label for="email">Email </label>
                </div>
                <div class="inputForm">
                    <x-carbon-at class="w-6 h-6 text-gray-500" />
                    <input type="email" name="email" class="input" placeholder="Ingresa tu email" required
                        value="{{ old('email') }}">
                </div>

                <div class="flex-column">
                    <label for="password">Contraseña</label>
                </div>
                <div class="inputForm">
                    <x-carbon-locked class="w-6 h-6 text-gray-500" />
                    <input type="password" class="input" placeholder="Escribe tu contraseña" name="password" required>
                    <x-carbon-view class="w-6 h-6 text-gray-500" />
                </div>

                <button type="submit" class="button-submit">Ingresar</button>
                <p class="p">¿No tiene una cuenta? <span class="span">Registrarse</span>

                </p>

                <!-- validation errors -->
                @if ($errors->any())
                    <ul class="px-4 py-2 bg-red-100">
                        @foreach ($errors->all() as $error)
                            <li class="my-2 text-red-500">{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
            </form>
        </div>
    </div>
@endsection
