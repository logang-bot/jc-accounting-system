<?php

namespace App\Http\Controllers;

use App\Models\Comprobante;
use App\Models\Empresa;
use Illuminate\Http\Request;

use function Spatie\LaravelPdf\Support\pdf;

class LibroDiarioController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'fecha_desde' => 'nullable|date',
            'fecha_hasta' => 'nullable|date',
            'tipo' => 'nullable|string',
        ]);

        $empresaId = session('empresa_id');

        // Solo para mostrar el mensaje en el Blade
        $hayFiltros = $request->filled('fecha_desde') ||
            $request->filled('fecha_hasta') ||
            $request->filled('tipo');

        // Consulta base
        $query = Comprobante::with(['detalles.cuenta', 'empresa', 'user'])
            ->where('empresa_id', $empresaId)
            ->when($request->fecha_desde, fn($q) => $q->where('fecha', '>=', $request->fecha_desde))
            ->when($request->fecha_hasta, fn($q) => $q->where('fecha', '<=', $request->fecha_hasta));

        // Filtro por tipo (maneja "SELECCIONE" y "TODOS")
        if ($request->filled('tipo') && $request->tipo !== 'todos') {
            $query->where('tipo', 'ILIKE', '%' . $request->tipo . '%');
        }

        $query->orderBy('fecha', 'asc')
            ->orderBy('numero', 'asc');

        $comprobantes = $query->paginate(50)->withQueryString();

        $collection = $comprobantes->getCollection()->flatMap(fn($c) => $c->detalles);
        $totales = [
            'debe'  => $collection->sum(fn($d) => $d->debe ?? 0),
            'haber' => $collection->sum(fn($d) => $d->haber ?? 0),
        ];

        return view('libroDiario.index', compact('comprobantes', 'totales', 'hayFiltros'));
    }

    public function exportPdf(Request $request)
    {
        $request->validate([
            'fecha_desde' => 'nullable|date',
            'fecha_hasta' => 'nullable|date',
            'tipo'        => 'nullable|string',
        ]);

        $empresaId = session('empresa_id');

        // Detectar si hay filtros REALES (fechas o tipo distinto de "Todos")
        $hayFiltros = $request->filled('fecha_desde') ||
            $request->filled('fecha_hasta') ||
            ($request->filled('tipo') && $request->tipo !== 'Todos');

        // Siempre construir la consulta base
        $query = Comprobante::with(['detalles.cuenta', 'empresa', 'user'])
            ->where('empresa_id', $empresaId)
            ->when($request->fecha_desde, fn($q) => $q->where('fecha', '>=', $request->fecha_desde))
            ->when($request->fecha_hasta, fn($q) => $q->where('fecha', '<=', $request->fecha_hasta))
            ->when($request->tipo && $request->tipo !== 'Todos', fn($q) => $q->where('tipo', $request->tipo))
            ->orderBy('fecha', 'asc')
            ->orderBy('numero', 'asc');

        // Obtener todos los comprobantes (aunque no haya filtros)
        $comprobantes = $query->get();

        $collection = $comprobantes->flatMap(fn($c) => $c->detalles);
        $totales = [
            'debe'  => $collection->sum(fn($d) => $d->debe ?? 0),
            'haber' => $collection->sum(fn($d) => $d->haber ?? 0),
        ];

        $pdf = pdf()->view('libroDiarioPDF', [
            'comprobantes' => $comprobantes,
            'totales'      => $totales,
            'filters'      => $request->only(['fecha_desde', 'fecha_hasta', 'tipo']),
            'hayFiltros'   => $hayFiltros,
        ]);

        return $pdf->name('libro_diario.pdf');
    }
}
