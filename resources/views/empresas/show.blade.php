@extends('layouts.auth')

@section('content')
    <div class="container mt-4">
        <h2>Detalles de la Empresa</h2>
        <div class="card mt-3">
            <div class="card-body">
                <p><strong>Nombre de Empresa:</strong> {{ $empresa->name }}</p>
                <a href="{{ route('show.empresas.create') }}" class="btn btn-primary mt-3">Volver</a>
            </div>
        </div>
    </div>
@endsection
