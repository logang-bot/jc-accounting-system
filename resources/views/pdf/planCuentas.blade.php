<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Plan de Cuentas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="text-sm font-sans">
    <!-- Header -->
    <div class="border-b border-gray-400 pb-4 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-lg font-bold">{{ $empresa->nombre }}</h2>
                <p class="text-xs">NIT: {{ $empresa->nit }}</p>
                <p class="text-xs">{{ $empresa->direccion }}, {{ $empresa->ciudad }} - {{ $empresa->provincia }}</p>
                <p class="text-xs">Tel: {{ $empresa->telefonos }} | Email: {{ $empresa->correo_electronico }}</p>
            </div>
            <div class="text-right">
                <p class="text-xs">Periodo: <span class="font-semibold">{{ $empresa->periodo }}</span></p>
                <p class="text-xs">Gestión: <span class="font-semibold">{{ $empresa->gestion }}</span></p>
                <p class="text-xs">Fecha: <span class="font-semibold">{{ now()->format('d/m/Y') }}</span></p>
            </div>
        </div>
    </div>

    <!-- Title -->
    <h1 class="text-center text-xl font-bold mb-6">Plan de Cuentas</h1>

    <!-- Table -->
    <table class="w-full border-collapse border border-gray-400">
        <thead class="bg-gray-100">
            <tr>
                <th class="border border-gray-400 px-2 py-1 text-left w-1/6">Código</th>
                <th class="border border-gray-400 px-2 py-1 text-left w-1/2">Nombre</th>
                <th class="border border-gray-400 px-2 py-1 text-center w-1/12">Nivel</th>
                <th class="border border-gray-400 px-2 py-1 text-center w-1/6">Movimiento</th>
                <th class="border border-gray-400 px-2 py-1 text-center w-1/6">Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cuentas as $cuenta)
                <tr>
                    <td class="border border-gray-400 px-2 py-1">
                        {{ $cuenta->codigo_cuenta }}
                    </td>
                    <td class="border border-gray-400 px-2 py-1">
                        {{ $cuenta->nombre_cuenta }}
                    </td>
                    <td class="border border-gray-400 px-2 py-1 text-center">
                        {{ $cuenta->nivel }}
                    </td>
                    <td class="border border-gray-400 px-2 py-1 text-center">
                        {{ $cuenta->es_movimiento ? 'Sí' : 'No' }}
                    </td>
                    <td class="border border-gray-400 px-2 py-1 text-center">
                        {{ $cuenta->estado == 'A' ? 'Activo' : 'Inactivo' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
