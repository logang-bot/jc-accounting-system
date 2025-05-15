<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('layouts.partials.admin.head')
    <title>Home</title>

    @vite('resources/css/empresas.css')
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>

<body class="bg-gray-100">
    <div id="db-wrapper" x-data="{ sidebarOpen: true }" class="flex">
        <!-- Navbar vertical -->
        @include('layouts.partials.admin.navbar-vertical-admin')

        <!-- Page content -->
        <div id="page-content" class="flex-1 flex flex-col">
            @include('layouts.partials.admin.header')
            @include('layouts.partials.admin.notifications')

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
