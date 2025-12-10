<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>Home</title>

    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>

<body class="bg-gray-100 {{ auth()->user()->roles->first()?->theme ?? 'theme-default' }}">
    <div x-data="{ sidebarOpen: true }" class="flex">
        <!-- Navbar vertical -->
        @include('layouts.partials.admin.navbar-vertical-admin')

        <!-- Page content -->
        <div id="page-content" class="flex-1 ml-0 transition-all duration-300" :class="sidebarOpen ? 'ml-64' : 'ml-0'">
            @include('layouts.partials.admin.header')

            <!-- Main content area -->
            <main class="flex-1">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    @include('layouts.partials.admin.scripts')
</body>

</html>
