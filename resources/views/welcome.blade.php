@extends('layouts.main')

@section('content')
    <div class="welcome-section">
        <nav class="hero-nav hero-container">
            <img src="{{ asset('assets/images/img_escudo.png') }}" class="logo-icon" />
        </nav>
        <div class="absolute left-[40%] top-1/2 transform -translate-x-1/2 -translate-y-1/2 leading-tight max-w-[1200px]">
            <div>
                <h1>Sistema contable de la UATF</h1>
                <p>
                    Bienvenido al sistema contable de Universidad Autonoma Tomas Frias, si dispone de una cuenta ingrese
                    sus credenciales en la seccion de Ingresar, caso contrario, por favor cree una nueva cuenta haciendo
                    click en el boton de Crear Cuenta
                </p>
                <a href="{{ route('show.login') }}">
                    <button class="login-button group">
                        <span>
                            Ingresar
                        </span>
                    </button>
                </a>
                <button type="button" class="register-button">
                    <a href="{{ route('show.register') }}">Register</a>
                </button>
            </div>
        </div>
    </div>
@endsection
