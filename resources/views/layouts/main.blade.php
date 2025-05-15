<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de contabilidad</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @yield('customstyles')
    @yield('customscripts')
</head>

<body>
    @yield('content')
</body>

</html>
