<?php

namespace App\Http\Controllers;

use App\Services\AccountingService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;


class BalancesController extends Controller
{

    protected $balanceService;

    public function __construct(AccountingService $balanceService)
    {
        $this->balanceService = $balanceService;
    }

    public function balanceGeneral(Request $request)
    {
        // Filters
        $empresaId   = session('empresa_id');
        $fechaDesde  = $request->input('fecha_desde');
        $fechaHasta  = $request->input('fecha_hasta');

        // Call service
        $balances = $this->balanceService->getBalances($empresaId, $fechaDesde, $fechaHasta);

        // Return to view
        return view('balances.general', compact('balances', 'fechaDesde', 'fechaHasta'));
    }

    public function exportPdf(Request $request)
    {
        $empresaId = session('empresa_id'); // o como tengas la empresa seleccionada
        $fechaDesde = $request->input('fecha_desde');
        $fechaHasta = $request->input('fecha_hasta');

        // Usar tu servicio para obtener los balances
        $balances = $this->balanceService->getBalances($empresaId, $fechaDesde, $fechaHasta);

        // Generar PDF (requiere barryvdh/laravel-dompdf)
        $pdf = Pdf::loadView('balanceGeneralPDF', compact('balances', 'fechaDesde', 'fechaHasta'));

        // Descargar PDF con nombre dinámico
        return $pdf->download('Balance_General_' . $fechaDesde . '_al_' . $fechaHasta . '.pdf');
    }
}
