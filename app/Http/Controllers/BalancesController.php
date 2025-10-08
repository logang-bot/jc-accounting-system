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
        // Filtros
        $empresaId  = session('empresa_id');
        $fechaDesde = $request->input('fecha_desde');
        $fechaHasta = $request->input('fecha_hasta');

        // 🔹 Obtener balances
        $balances = $this->balanceService->getBalances($empresaId, $fechaDesde, $fechaHasta);

        // 🔹 🔹 AQUÍ AGREGAR LA LÓGICA DEL RESULTADO NETO 🔹 🔹
        // Obtener el resultado neto del Estado de Resultados
        $resultados = app(\App\Http\Controllers\EstadoResultadosController::class)
            ->calcularEstadoResultados($empresaId, $fechaDesde, $fechaHasta);

        $resultadoNeto = $resultados['resultado_neto'] ?? 0;

        // Agregar el resultado neto como una subcuenta de patrimonio
        $balances['patrimonio'][] = [
            'codigo' => '3501010002',             // Excedentes del Ejercicio
            'nombre' => 'Excedentes del Ejercicio',
            'nivel' => 5,
            'saldo' => $resultadoNeto,
            'full_parent_chain' => ['Excedentes', 'Excedentes Contables', 'Componentes de Excedentes']
        ];

        // Recalcular total patrimonio
        $balances['total_patrimonio'] = ($balances['total_patrimonio'] ?? 0) + $resultadoNeto;

        // 🔹 Recalcular total Pasivo + Patrimonio
        $balances['total_pasivos_patrimonio'] =
            ($balances['total_pasivos'] ?? 0) + ($balances['total_patrimonio'] ?? 0);

        // 🔹 Retornar vista
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
