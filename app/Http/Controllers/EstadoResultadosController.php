<?php

namespace App\Http\Controllers;

use App\Services\AccountingService;
use Illuminate\Http\Request;

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
}
