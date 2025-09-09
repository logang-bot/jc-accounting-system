@extends('layouts.main')

@section('content')
    <div class="max-w-3xl mx-auto">
        <h1 class="text-xl font-bold mb-4">Users</h1>

        <a href="{{ route('admin.show.users.create') }}" class="px-3 py-2 bg-green-600 text-white rounded">New User</a>

        <table class="w-full mt-4 border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-3 py-2 text-left">Name</th>
                    <th class="px-3 py-2 text-left">Email</th>
                    <th class="px-3 py-2 text-left">Role(s)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr class="border-t">
                        <td class="px-3 py-2">{{ $user->name }}</td>
                        <td class="px-3 py-2">{{ $user->email }}</td>
                        <td class="px-3 py-2">{{ $user->getRoleNames()->implode(', ') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            {{ $users->links() }}
        </div>
    </div>
@endsection
