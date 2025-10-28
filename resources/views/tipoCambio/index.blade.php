@extends('layouts.admin')

@section('content')
    <div class="w-full mx-auto">
        <div class="flex justify-between items-center bg-blue-600 px-10 py-5">
            <h3 class="text-white text-2xl font-semibold">Historial de tipo de cambios</h3>
        </div>

        @if (session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-row gap-5 p-6">
            <div class="flex-1">
                <h1 class="text-2xl font-bold mb-4">Nuevo Tipo de Cambio</h1>

                <form action="{{ route('tipo-cambio.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="fecha" class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
                        <input type="date" id="fecha" name="fecha" value="{{ old('fecha') }}"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3">
                        @error('fecha')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="valor_ufv" class="block text-sm font-medium text-gray-700 mb-1">Valor UFV (Bs)</label>
                        <input type="number" step="0.01" id="valor_ufv" name="valor_ufv" value="{{ old('valor_ufv') }}"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3">
                        @error('valor_ufv')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="valor_sus" class="block text-sm font-medium text-gray-700 mb-1">Valor SUS (Bs)</label>
                        <input type="number" step="0.01" id="valor_sus" name="valor_sus" value="{{ old('valor_sus') }}"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3">
                        @error('valor_sus')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Guardar</button>
                    </div>
                </form>
            </div>

            <div class="flex-2 flex flex-col gap-6">
                <div class="w-full p-6 bg-white rounded-lg shadow flex flex-col items-center">
                    <h2 class="text-2xl font-bold mb-6 text-gray-800">Tipos de Cambio Actuales</h2>

                    <!-- USD -->
                    <div class="mb-6 text-center">
                        <div class="text-xl text-gray-600">USD (Bs)</div>
                        <div id="valorUsd" class="text-4xl font-bold text-blue-600"></div>
                    </div>

                    <!-- UFV -->
                    <div class="mb-6 text-center">
                        <div class="text-xl text-gray-600">UFV (Bs)</div>
                        <div id="valorUfv" class="text-4xl font-bold text-green-600">{{ $valorUfv }}</div>
                    </div>

                    <div class="mt-4 text-sm text-gray-500">
                        Fuente: <a href="https://www.bcb.gob.bo/?q=ufv" target="_blank"
                            class="text-blue-600 underline">bcb.gob.bo</a>
                    </div>
                </div>

                <div class="flex justify-between items-center bg-blue-600 px-10 py-5">
                    <h3 class="text-white text-2xl font-semibold">Historial de registros</h3>
                </div>

                <table class="w-full border-collapse border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border p-2 text-left">Fecha</th>
                            <th class="border p-2 text-right">Valor UFV (Bs)</th>
                            <th class="border p-2 text-right">Valor SUS (Bs)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($registros as $registro)
                            <tr class="border-b">
                                <td class="border p-2">{{ $registro->fecha->format('d/m/Y') }}</td>
                                <td class="border p-2 text-right">{{ number_format($registro->valor_ufv, 6) }}</td>
                                <td class="border p-2 text-right">{{ number_format($registro->valor_sus, 6) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center p-4 text-gray-500">No hay registros aún.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <script>
        // document.addEventListener('DOMContentLoaded', async () => {
        //     try {

        //         const today = new Date();

        //         const day = String(today.getDate()).padStart(2, '0');
        //         const month = String(today.getMonth() + 1).padStart(2, '0'); // Months are 0-indexed
        //         const year = today.getFullYear();

        //         const formattedDate = `${day}/${month}/${year}`;

        //         const ufvResponse = await fetch(
        //             "https://www.bcb.gob.bo/?q=tc"
        //         );

        //         const ufvHtml = await ufvResponse.text();
        //         const ufvDoc = new DOMParser().parseFromString(ufvHtml, "text/html");
        //         const ufvElement = ufvDoc.querySelector("table tr:nth-child(2) td:nth-child(2)");
        //         const valorUfv = ufvElement ? ufvElement.textContent.trim() : "No disponible";

        //         document.getElementById("valorUfv").textContent = valorUfv;

        //         // --- Fetch USD / BOB ---
        //         const usdResponse = await fetch("https://www.bcb.gob.bo/?q=tc");
        //         const usdHtml = await usdResponse.text();
        //         const usdDoc = new DOMParser().parseFromString(usdHtml, "text/html");
        //         const usdElement = usdDoc.querySelector("table tr:nth-child(2) td:nth-child(2)");
        //         const valorUsd = usdElement ? usdElement.textContent.trim() : "No disponible";

        //         document.getElementById("valorUsd").textContent = valorUsd;

        //     } catch (error) {
        //         console.error("Error al obtener los valores:", error);
        //         document.getElementById("valorUfv").textContent = "Error";
        //         document.getElementById("valorUsd").textContent = "Error";
        //     }
        // });
    </script>
@endsection
