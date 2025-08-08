<?php

namespace App\Http\Controllers;

use App\Models\Comprobante;
use Illuminate\Http\Request;

class LibroDiarioController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'fecha_desde' => 'nullable|date',
            'fecha_hasta' => 'nullable|date',
            'empresa_id' => 'nullable|integer|exists:empresas,id',
            'cuenta_id' => 'nullable|integer|exists:cuentas,id',
            'tipo' => 'nullable|string',
        ]);

        $query = Comprobante::with(['detalles.cuenta','empresa','user'])
            ->when($request->fecha_desde, fn($q) => $q->where('fecha', '>=', $request->fecha_desde))
            ->when($request->fecha_hasta, fn($q) => $q->where('fecha', '<=', $request->fecha_hasta))
            ->when($request->empresa_id, fn($q) => $q->where('empresa_id', $request->empresa_id))
            ->when($request->tipo, fn($q) => $q->where('tipo', $request->tipo))
            ->orderBy('fecha', 'asc')
            ->orderBy('numero', 'asc');

        $comprobantes = $query->paginate(50)->withQueryString();

        // Totales para la página (ajusta campos 'debe' y 'haber' a los tuyos)
        $collection = $comprobantes->getCollection()->flatMap(fn($c) => $c->detalles);
        $totales = [
            'debe'  => $collection->sum(fn($d) => $d->debe ?? 0),
            'haber' => $collection->sum(fn($d) => $d->haber ?? 0),
        ];

        return view('libroDiario.index', compact('comprobantes','totales'));
    }
}
