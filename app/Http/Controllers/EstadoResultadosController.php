<?php

namespace App\Http\Controllers;

use App\Services\AccountingService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

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
        // Aquí reutilizas la misma lógica de tu vista (filtros, cálculos, etc.)
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin    = $request->get('fecha_fin');

        $ingresos = [
            ['codigo' => '4120201010', 'nombre' => 'HONORARIOS POR SERVICIOS', 'saldo' => 1200.00],
        ];

        $egresos = [
            ['codigo' => '5110101012', 'nombre' => 'GASTOS DE OFICINA', 'saldo' => 86.50],
        ];

        $totalIngresos = collect($ingresos)->sum('saldo');
        $totalEgresos  = collect($egresos)->sum('saldo');
        $resultadoNeto = $totalIngresos - $totalEgresos;

        $pdf = Pdf::loadView('reportes.estado_resultados_pdf', compact(
            'fechaInicio',
            'fechaFin',
            'ingresos',
            'egresos',
            'totalIngresos',
            'totalEgresos',
            'resultadoNeto'
        ));

        return $pdf->stream("Estado_Resultados_{$fechaInicio}_{$fechaFin}.pdf");
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
