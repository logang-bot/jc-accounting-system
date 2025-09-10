@extends('layouts.main')

@section('content')
    <div class="max-w-xl mx-auto">
        <h1 class="text-xl font-bold mb-4">Create Usfgher</h1>

        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="block">Name</label>
                <input type="text" name="name" class="border rounded w-full p-2" required>
            </div>

            <div class="mb-3">
                <label class="block">Email</label>
                <input type="email" name="email" class="border rounded w-full p-2" required>
            </div>

            <div class="mb-3">
                <label class="block">Password</label>
                <input type="password" name="password" class="border rounded w-full p-2" required>
            </div>

            <div class="mb-3">
                <label class="block">Confirm Password</label>
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

            <button class="px-4 py-2 bg-indigo-600 text-white rounded">Create</button>
        </form>
    </div>
@endsection
