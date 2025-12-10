<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comprobante;
use App\Models\CuentasContables;
use Barryvdh\DomPDF\Facade\Pdf;

class BalancesController extends Controller
{
    /**
     * Mostrar el balance general filtrado por fechas
     */
    public function balanceGeneral(Request $request)
    {
        $empresaId  = session('empresa_id');
        $fechaDesde = $request->input('fecha_desde');
        $fechaHasta = $request->input('fecha_hasta');

        // 🔹 Obtener comprobantes y detalles con cuentas
        $comprobantes = Comprobante::where('empresa_id', $empresaId)
            ->when($fechaDesde, fn($q) => $q->whereDate('fecha', '>=', $fechaDesde))
            ->when($fechaHasta, fn($q) => $q->whereDate('fecha', '<=', $fechaHasta))
            ->with('detalles.cuenta') // relación correcta
            ->get();

        // 🔹 Inicializar arrays para los balances
        $balances = [
            'activos' => [],
            'pasivos' => [],
            'patrimonio' => [],
            'total_activos' => 0,
            'total_pasivos' => 0,
            'total_patrimonio' => 0,
        ];

        // 🔹 Recorrer cuentas y sumar saldos
        $cuentas = CuentasContables::where('empresa_id', $empresaId)->get();

        foreach ($cuentas as $cuenta) {
            $saldo = 0;

            // Buscar detalles de comprobantes para esta cuenta
            foreach ($comprobantes as $comprobante) {
                foreach ($comprobante->detalles as $detalle) {
                    if ($detalle->cuenta_contable_id == $cuenta->id_cuenta) {
                        $saldo += $detalle->debe_bs - $detalle->haber_bs;
                    }
                }
            }

            // Clasificar según grupo (primer dígito del código de cuenta)
            $grupo = substr($cuenta->codigo_cuenta, 0, 1);

            if ($grupo == '1') { // Activos
                $balances['activos'][] = [
                    'codigo_cuenta' => $cuenta->codigo_cuenta,
                    'nombre' => $cuenta->nombre_cuenta,
                    'saldo' => $saldo,
                ];
                $balances['total_activos'] += $saldo;
            } elseif ($grupo == '2') { // Pasivos
                $balances['pasivos'][] = [
                    'codigo_cuenta' => $cuenta->codigo_cuenta,
                    'nombre' => $cuenta->nombre_cuenta,
                    'saldo' => $saldo,
                ];
                $balances['total_pasivos'] += $saldo;
            } elseif ($grupo == '3') { // Patrimonio
                $balances['patrimonio'][] = [
                    'codigo_cuenta' => $cuenta->codigo_cuenta,
                    'nombre' => $cuenta->nombre_cuenta,
                    'saldo' => $saldo,
                ];
                $balances['total_patrimonio'] += $saldo;
            }
        }
        // 🔹 Calcular resultado neto del Estado de Resultados
        $resultados = app(\App\Http\Controllers\EstadoResultadosController::class)
            ->calcularEstadoResultados($empresaId, $fechaDesde, $fechaHasta);
        $resultadoNeto = $resultados['resultado_neto'] ?? 0;

        // 🔹 Agregar Resultado de Ejercicios al patrimonio si no es cero
        if ($resultadoNeto != 0) {
            $balances['patrimonio'][] = [
                'codigo_cuenta' => '3301010000',
                'nombre' => 'Resultado de Ejercicios',
                'saldo' => $resultadoNeto,
            ];
            $balances['total_patrimonio'] += $resultadoNeto;
        }

        // 🔹 Recalcular total Pasivos + Patrimonio incluyendo resultado neto
        $balances['total_pasivos_patrimonio'] = $balances['total_pasivos'] + $balances['total_patrimonio'];

        // 🔹 Calcular total Pasivos + Patrimonio
        $balances['total_pasivos_patrimonio'] = $balances['total_pasivos'] + $balances['total_patrimonio'];

        return view('balances.general', compact('balances', 'fechaDesde', 'fechaHasta'));
    }

    /**
     * Exportar balance general a PDF
     */
    public function exportPdf(Request $request)
    {
        $empresaId  = session('empresa_id');
        $fechaDesde = $request->input('fecha_desde');
        $fechaHasta = $request->input('fecha_hasta');

        // 🔹 Reutilizar el método balanceGeneral para obtener balances
        $requestData = new Request([
            'fecha_desde' => $fechaDesde,
            'fecha_hasta' => $fechaHasta,
        ]);
        $controller = new self();
        $response = $controller->balanceGeneral($requestData);
        $balances = $response->getData()['balances'];

        // 🔹 Generar PDF
        $pdf = Pdf::loadView('balanceGeneralPDF', compact('balances', 'fechaDesde', 'fechaHasta'));

        return $pdf->download('Balance_General_' . ($fechaDesde ?? 'inicio') . '_al_' . ($fechaHasta ?? 'fin') . '.pdf');
    }
}
