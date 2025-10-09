@extends('layouts.admin')

@section('customscripts')
    @vite('resources/js/empresasCreate.js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('content')
    @if (session('success'))
        <div id="flash" class="p-4 text-center bg-green-50 text-green-500 font-bold">
            {{ session('success') }}
        </div>
    @endif
    <div class="max-w-7xl mx-auto p-6 m-6 bg-white shadow-md rounded-xl">
        <h2 class="text-2xl font-semibold mb-6">
            Crear usuario
        </h2>

        <form action="{{ route('admin.usuarios.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="block">Nombre</label>
                <input type="text" name="name" class="border rounded w-full p-2" required>
            </div>

            <div class="mb-3">
                <label class="block">Email</label>
                <input type="email" name="email" class="border rounded w-full p-2" required>
            </div>

            <div class="mb-3">
                <label class="block">Contraseña</label>
                <input type="password" name="password" class="border rounded w-full p-2" required>
            </div>

            <div class="mb-3">
                <label class="block">Confirmar Contraseña</label>
                <input type="password" name="password_confirmation" class="border rounded w-full p-2" required>
            </div>

            <div class="mb-3">
                <label class="block">Role</label>
                <select name="role" class="border rounded w-full p-2" required>
                    @foreach ($roles as $role)
                        <option value="{{ $role }}">{{ $role }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Crear</button>
        </form>
    </div>
@endsection
