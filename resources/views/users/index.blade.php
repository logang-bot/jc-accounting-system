@extends('layouts.admin')

@section('content')
    <div class="bg-white">
        <div class="w-full mx-auto">
            <div class="flex flex-col gap-6">
                <!-- Header -->
                <div class="flex justify-between items-center bg-blue-600 px-10 py-5">
                    <h3 class="text-white text-2xl font-semibold">Lista de usuarios</h3>
                </div>
                <div class="max-w-3xl mx-auto">
                    <table class="min-w-150 divide-y divide-gray-200 dark:divide-neutral-500">
                        <thead class="bg-gray-800 text-white text-sm font-semibold">
                            <tr>
                                <th class="px-6 py-3 text-start text-xs font-medium  uppercase">
                                    Nombre</th>
                                <th class="px-6 py-3 text-start text-xs font-medium  uppercase">
                                    Email</th>
                                <th class="px-6 py-3 text-start text-xs font-medium  uppercase">
                                    Role(s)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr class="odd:bg-white even:bg-black/10 hover:bg-gray-100">
                                    <td class="px-3 py-2">{{ $user->name }}</td>
                                    <td class="px-3 py-2">{{ $user->email }}</td>
                                    <td class="px-3 py-2">
                                        {{ $user->getRoleNames()->implode(', ') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- <div class="mt-3">
            {{ $users->links() }}
        </div> --}}
                </div>
            </div>
        </div>
    </div>
@endsection
