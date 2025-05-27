@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-6 py-6">
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-2xl font-bold mb-4">Detalles del Comprobante</h2>

            <div class="mb-6">
                <p><strong>Número:</strong> {{ $comprobante->numero }}</p>
                <p><strong>Fecha:</strong> {{ $comprobante->fecha }}</p>
                <p><strong>Tipo:</strong> {{ ucfirst($comprobante->tipo) }}</p>
                <p><strong>Descripción:</strong> {{ $comprobante->descripcion }}</p>
                <p><strong>Total:</strong> {{ number_format($comprobante->total, 2) }}</p>
                <p><strong>Creado por:</strong> {{ $comprobante->user->name ?? '—' }}</p>
            </div>

            <h3 class="text-xl font-semibold mb-2">Detalle del Comprobante</h3>
            <table class="min-w-full border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border p-2">Cuenta</th>
                        <th class="border p-2">Descripción</th>
                        <th class="border p-2">Debe</th>
                        <th class="border p-2">Haber</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($comprobante->detalles as $detalle)
                        <tr>
                            <td class="border p-2">{{ $detalle->cuenta->nombre ?? '-' }}</td>
                            <td class="border p-2">{{ $detalle->descripcion }}</td>
                            <td class="border p-2 text-right">{{ number_format($detalle->debe, 2) }}</td>
                            <td class="border p-2 text-right">{{ number_format($detalle->haber, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                <a href="{{ route('show.comprobantes.home') }}" class="text-blue-600 hover:underline">← Volver a la
                    lista</a>
            </div>
        </div>
    </div>
@endsection
