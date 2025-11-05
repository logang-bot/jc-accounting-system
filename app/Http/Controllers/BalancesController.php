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
        // 🔹 Filtros
        $empresaId  = session('empresa_id');
        $fechaDesde = $request->input('fecha_desde');
        $fechaHasta = $request->input('fecha_hasta');

        // 🔹 Obtener balances
        $balances = $this->balanceService->getBalances($empresaId, $fechaDesde, $fechaHasta);

        // 🔹 Obtener el resultado neto del Estado de Resultados
        $resultados = app(\App\Http\Controllers\EstadoResultadosController::class)
            ->calcularEstadoResultados($empresaId, $fechaDesde, $fechaHasta);

        $resultadoNeto = $resultados['resultado_neto'] ?? 0;

        // 🔹 Obtener jerarquía real de la cuenta “Resultado de Ejercicios”
        $cuentaResultado = \App\Models\CuentasContables::where('codigo_cuenta', '3301010000')
            ->where('empresa_id', $empresaId)
            ->with('parent.parent.parent') // incluir toda la jerarquía
            ->first();

        $fullParentChain = [];
        if ($cuentaResultado) {
            $current = $cuentaResultado->parent;
            while ($current) {
                $fullParentChain[] = [
                    'codigo' => $current->codigo_cuenta,
                    'nombre' => $current->nombre_cuenta,
                ];
                $current = $current->parent;
            }
            $fullParentChain = array_reverse($fullParentChain);
        }

        // 🔹 Agregar el Resultado de Ejercicios como subcuenta de patrimonio (con su jerarquía completa)
        $balances['patrimonio'][] = [
            'codigo_cuenta' => '3301010000',
            'nombre' => 'Resultado de Ejercicios',
            'nivel' => 4,
            'saldo' => $resultadoNeto,
            'full_parent_chain' => $fullParentChain,
        ];

        // 🔹 Recalcular totales
        $balances['total_patrimonio'] = ($balances['total_patrimonio'] ?? 0) + $resultadoNeto;
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
