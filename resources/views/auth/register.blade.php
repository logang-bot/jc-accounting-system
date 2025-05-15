@extends('layouts.main')

@section('customstyles')
    @vite('resources/css/login.css')
@endsection

@section('content')
    @if (session('success'))
        <div id="flash" class="p-4 text-center bg-green-50 text-green-500 font-bold">
            {{ session('success') }}
        </div>
    @endif

    <div class="content">
        <form class="form" action="{{ route('register') }}" method="POST">
            @csrf
            <h2 class="text-center text-2xl font-semibold text-gray-800">Crear cuenta</h2>

            <div class="flex-column">
                <label for="email">Nombre </label>
            </div>
            <div class="inputForm">
                <x-carbon-user class="w-6 h-6 text-gray-500" />
                <input type="text" name="name" class="input" placeholder="Ingresa tu nombre" required
                    value="{{ old('name') }}">
            </div>

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

            <div class="flex-column">
                <label for="password">Confirmar Contraseña</label>
            </div>
            <div class="inputForm">
                <x-carbon-locked class="w-6 h-6 text-gray-500" />
                <input type="password" class="input" placeholder="Confirma tu contraseña" name="password_confirmation"
                    required>
                <x-carbon-view class="w-6 h-6 text-gray-500" />
            </div>

            <button type="submit" class="button-submit">Registrarse</button>
            <p class="p">¿Ya tienes una cuenta? <span class="span">Ingresar</span>

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
@endsection
