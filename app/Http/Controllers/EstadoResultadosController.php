<?php

namespace App\Http\Controllers;

use App\Services\AccountingService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class EstadoResultadosController extends Controller
{
    protected $service;

    public function __construct(AccountingService $service)
    {
        $this->service = $service;
    }

    /**
     * Display the Estado de Resultados.
     */
    public function index(Request $request)
    {
        $request->validate([
            'fecha_desde' => 'nullable|date',
            'fecha_hasta' => 'nullable|date',
        ]);

        $empresaId = session('empresa_id'); // or however you get the current company

        $fechaDesde = $request->input('fecha_desde');
        $fechaHasta = $request->input('fecha_hasta');

        $resultados = $this->service->getEstadoResultados($empresaId, $fechaDesde, $fechaHasta);

        return view('reportes.estado_resultados', [
            'resultados' => $resultados,
            'fechaDesde' => $fechaDesde,
            'fechaHasta' => $fechaHasta,
        ]);
    }

    public function exportarPDF(Request $request)
    {
        $empresaId = session('empresa_id');
        $fechaDesde = $request->input('fecha_desde');
        $fechaHasta = $request->input('fecha_hasta');

        // Obtenemos los resultados filtrados
        $resultados = $this->service->getEstadoResultados($empresaId, $fechaDesde, $fechaHasta);

        // Pasamos todo al PDF
        $pdf = Pdf::loadView('reportes.estado_resultados_pdf', [
            'resultados' => $resultados,
            'fechaDesde' => $fechaDesde,
            'fechaHasta' => $fechaHasta,
        ]);

        return $pdf->stream('estado_resultados.pdf');
    }

    public function calcularEstadoResultados($empresaId, $fechaDesde, $fechaHasta)
    {
        // Reutiliza el mismo servicio que ya tienes
        $resultados = $this->service->getEstadoResultados($empresaId, $fechaDesde, $fechaHasta);

        // Asegura que retorne un arreglo con 'resultado_neto'
        if (!isset($resultados['resultado_neto'])) {
            $totalIngresos = $resultados['total_ingresos'] ?? 0;
            $totalEgresos  = $resultados['total_egresos'] ?? 0;
            $resultados['resultado_neto'] = $totalIngresos - $totalEgresos;
        }

        return $resultados;
    }
}
