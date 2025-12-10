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
            Editar usuario
        </h2>

        <form action="{{ route('admin.usuarios.update', $usuario->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="block">Nombre</label>
                <input type="text" name="name" class="border rounded w-full p-2"
                    value="{{ old('name', $usuario->name ?? '') }}" required>
                @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-3">
                <label class="block">Email</label>
                <input type="email" name="email" class="border rounded w-full p-2"
                    value="{{ old('email', $usuario->email ?? '') }}" required>
                @error('email')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-3">
                <label class="block">Contraseña</label>
                <input type="password" name="password" class="border rounded w-full p-2" required>
                @error('password')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-3">
                <label class="block">Confirmar Contraseña</label>
                <input type="password" name="password_confirmation" class="border rounded w-full p-2" required>
                @error('password_confirmation')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-3">
                <label class="block">Role</label>
                <select name="role" class="border rounded w-full p-2" required>
                    @foreach ($roles as $id => $name)
                        <option value="{{ $name }}"
                            {{ $usuario->roles->first() && $usuario->roles->first()->id == $id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
                @error('role')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

        </form>
        <button type="submit" class="px-4 py-2 my-5 bg-indigo-600 text-white rounded">Actualizar</button>
        <form action="{{ route('admin.usuarios.destroy', $usuario->id) }}" method="POST"
            onsubmit="return confirm('¿Estás seguro de eliminar este usuario? Esta acción no se puede deshacer.')">
            @csrf
            @method('DELETE')

            <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded">
                Eliminar Usuario
            </button>
        </form>
    </div>
@endsection
