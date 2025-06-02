@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-6 py-6">
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex flex-row justify-between items-center mb-4">
                <h2 class="text-2xl font-bold">Detalles del Comprobante</h2>
                <a href="" class="bg-blue-500 text-white px-3 py-1 rounded text-sm">Imprimir</a>
            </div>
            <div class="mb-6">
                <p><strong>Número:</strong> {{ $comprobante->numero }}</p>
                <p><strong>Fecha:</strong> {{ $comprobante->fecha }}</p>
                <p><strong>Tipo:</strong> {{ ucfirst($comprobante->tipo) }}</p>
                <p><strong>Descripción:</strong> {{ $comprobante->descripcion }}</p>
                <p><strong>Total:</strong> {{ number_format($comprobante->total, 2) }}</p>
                <p><strong>Creado por:</strong> {{ $comprobante->user->name ?? '—' }}</p>
            </div>
            <div class="flex items-center gap-4 my-4">
                <label for="tasa-cambio" class="text-sm font-medium">Tasa de cambio:</label>
                <input type="number" step="0.0001" id="tasa-cambio" class="border rounded px-3 py-1 w-32" value="6.96">
                <button type="button" onclick="actualizarConversiones()"
                    class="bg-blue-600 text-white px-4 py-1 rounded hover:bg-blue-700">
                    Actualizar $us
                </button>
            </div>

            <h3 class="text-xl font-semibold mb-2">Detalle del Comprobante</h3>
            <table class="min-w-full border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border p-2">Cuenta</th>
                        <th class="border p-2">Nombre de cuenta</th>
                        <th class="border p-2">Glosa individual</th>
                        <th class="border p-2">Debe Bs.</th>
                        <th class="border p-2">Haber Bs.</th>
                        <th class="border p-2">Debe $us</th>
                        <th class="border p-2">Haber $us</th>
                    </tr>
                </thead>
                <tbody id="detalle-comprobante-table-body">
                    @foreach ($comprobante->detalles as $detalle)
                        <tr>
                            <td class="border p-2">{{ $detalle->cuenta->codigo_cuenta ?? '-' }}</td>
                            <td class="border p-2">{{ $detalle->cuenta->nombre_cuenta ?? '-' }}</td>
                            <td class="border p-2">{{ $detalle->descripcion }}</td>
                            <td class="border p-2 text-right debe-bs" data-original="{{ $detalle->debe }}">
                                {{ number_format($detalle->debe, 2) }}</td>
                            <td class="border p-2 text-right haber-bs" data-original="{{ $detalle->haber }}">
                                {{ number_format($detalle->haber, 2) }}</td>
                            <td class="border p-2 text-right us-debe">{{ number_format($detalle->debe / 6.96, 2) }}</td>
                            <td class="border p-2 text-right us-haber">{{ number_format($detalle->haber / 6.96, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-100 font-semibold">
                    <tr>
                        <td colspan="3" class="px-3 py-2 text-center">Totales:</td>
                        <td class="px-3 py-2 text-right" id="total-debe-bs">
                            {{ number_format($comprobante->detalles->sum('debe'), 2) }}
                        </td>
                        <td class="px-3 py-2 text-right" id="total-haber-bs">
                            {{ number_format($comprobante->detalles->sum('haber'), 2) }}
                        </td>
                        <td class="px-3 py-2 text-right" id="total-debe-us">
                            {{ number_format($comprobante->detalles->sum('debe') / 6.96, 2) }}
                        </td>
                        <td class="px-3 py-2 text-right" id="total-haber-us">
                            {{ number_format($comprobante->detalles->sum('haber') / 6.96, 2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>

            <div class="mt-4">
                <a href="{{ route('show.comprobantes.home') }}" class="text-blue-600 hover:underline">← Volver a la
                    lista</a>
            </div>
        </div>
    </div>
    <script>
        let tasaAnterior = null;

        function actualizarConversiones() {
            const tasaInput = document.getElementById('tasa-cambio');
            const tasa = parseFloat(tasaInput.value);

            if (!tasa || tasa <= 0) {
                alert('Ingrese una tasa de cambio válida.');
                return;
            }

            if (tasa === tasaAnterior) {
                console.log("La tasa no cambió. No se actualiza.");
                return;
            }

            let totalDebeBs = 0;
            let totalHaberBs = 0;

            document.querySelectorAll('#detalle-comprobante-table-body tr').forEach(fila => {
                const tdDebeBs = fila.querySelector('.debe-bs');
                const tdHaberBs = fila.querySelector('.haber-bs');
                const tdUsDebe = fila.querySelector('.us-debe');
                const tdUsHaber = fila.querySelector('.us-haber');

                const debeBs = parseFloat(tdDebeBs.dataset.original) || 0;
                const haberBs = parseFloat(tdHaberBs.dataset.original) || 0;

                const usDebe = debeBs / tasa;
                const usHaber = haberBs / tasa;

                tdUsDebe.innerText = usDebe.toFixed(2);
                tdUsHaber.innerText = usHaber.toFixed(2);

                totalDebeBs += debeBs;
                totalHaberBs += haberBs;
            });

            document.getElementById('total-debe-us').innerText = (totalDebeBs / tasa).toFixed(2);
            document.getElementById('total-haber-us').innerText = (totalHaberBs / tasa).toFixed(2);

            tasaAnterior = tasa;
        }
    </script>
@endsection
