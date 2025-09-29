<?php

namespace App\Http\Controllers;

use App\Services\AccountingService;
use Illuminate\Http\Request;

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
}
