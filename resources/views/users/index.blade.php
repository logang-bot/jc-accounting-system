@extends('layouts.admin')

@section('content')
    <div class="bg-white">
        <!-- Header -->
        <div class="flex justify-between items-center bg-[var(--sidebar-bg)] px-10 py-5">
            <h3 class="text-white text-2xl font-semibold">Gestion de usuarios</h3>
        </div>
        @if (session('success'))
            <div id="flash" class="p-4 text-center bg-green-50 text-green-500 font-bold">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                {{ session('error') }}
            </div>
        @endif
        <div class="flex flex-row gap-5 p-6">
            <div class="flex-1">
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
            </div>

            <div class="w-full flex-1">
                <table class="overflow-y-auto max-h-[760px] w-full divide-gray-200 dark:divide-neutral-500">
                    <thead class="bg-gray-800 text-white text-sm font-semibold">
                        <tr>
                            <th class="px-6 py-3 text-start text-xs font-medium uppercase">
                                Nombre</th>
                            <th class="px-6 py-3 text-start text-xs font-medium uppercase">
                                Email</th>
                            <th class="px-6 py-3 text-start text-xs font-medium uppercase">
                                Rol</th>
                            <th class="px-6 py-3 text-start text-xs font-medium uppercase">
                                Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr class="odd:bg-white even:bg-black/10 hover:bg-gray-100">
                                <td class="px-6 py-2">{{ $user->name }}</td>
                                <td class="px-6 py-2">{{ $user->email }}</td>
                                <td class="px-6 py-2">
                                    {{ $user->getRoleNames()->implode(', ') }}</td>
                                <td>
                                    <a href="{{ route('admin.show.usuarios.edit', $user->id) }}"
                                        class=" px-6 text-sm font-semibold rounded-lg border border-transparent text-blue-600 hover:text-blue-800 focus:outline-hidden focus:text-blue-800 disabled:opacity-50 disabled:pointer-events-none ">Revisar</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
