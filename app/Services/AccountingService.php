<?php

namespace App\Services;

use App\Models\ComprobanteDetalles;
use App\Models\CuentasContables;
use Illuminate\Support\Facades\DB;

class AccountingService
{
    public function getBalances($empresaId, $fechaDesde = null, $fechaHasta = null)
    {
        // Step 1: get all accounts for the empresa
        $cuentas = CuentasContables::where('empresa_id', $empresaId)
            ->select('id_cuenta', 'codigo_cuenta', 'nombre_cuenta', 'tipo_cuenta', 'parent_id', 'nivel')
            ->get();

        // Step 2: compute saldo for each account using ComprobanteDetalles
        $query = $cuentas->map(function ($cuenta) use ($fechaDesde, $fechaHasta) {
            $movs = ComprobanteDetalles::where('cuenta_contable_id', $cuenta->id_cuenta)
                ->when($fechaDesde, fn($q) => $q->whereDate('created_at', '>=', $fechaDesde))
                ->when($fechaHasta, fn($q) => $q->whereDate('created_at', '<=', $fechaHasta))
                ->get();

            $totalDebe = $movs->sum('debe');
            $totalHaber = $movs->sum('haber');

            // Compute saldo according to tipo_cuenta
            $saldo = ($cuenta->tipo_cuenta === 'Activo')
                ? $totalDebe - $totalHaber
                : $totalHaber - $totalDebe;

            return [
                'cuenta' => $cuenta,
                'codigo_cuenta' => $cuenta->codigo_cuenta,
                'nombre' => $cuenta->nombre_cuenta,
                'tipo_cuenta' => $cuenta->tipo_cuenta,
                'saldo' => $saldo,
                'nivel' => $cuenta->nivel
            ];
        });

        // Step 3: filter out accounts with saldo = 0
        $query = $query->filter(fn($row) => $row['saldo'] != 0);

        // Step 4: add full parent chain for display
        $query = $query->map(function ($row) {
            $parentChain = [];
            $current = $row['cuenta'];
            while ($current->parent) {
                $parentChain[] = [
                    'codigo' => $current->parent->codigo_cuenta,
                    'nombre' => $current->parent->nombre_cuenta
                ];
                $current = $current->parent;
            }
            $row['full_parent_chain'] = array_reverse($parentChain); // top-down order
            return $row;
        });

        // Step 5: group by tipo_cuenta and compute totals
        $balances = [
            'activos' => [],
            'total_activos' => 0,
            'pasivos' => [],
            'total_pasivos' => 0,
            'patrimonio' => [],
            'total_patrimonio' => 0,
        ];

        foreach ($query as $row) {
            $data = [
                'codigo_cuenta' => $row['codigo_cuenta'],
                'nombre' => $row['nombre'],
                'saldo' => $row['saldo'],
                'full_parent_chain' => $row['full_parent_chain'],
                'nivel' => $row['nivel']
            ];

            switch ($row['tipo_cuenta']) {
                case 'Activo':
                    $balances['activos'][] = $data;
                    $balances['total_activos'] += $row['saldo'];
                    break;
                case 'Pasivo':
                    $balances['pasivos'][] = $data;
                    $balances['total_pasivos'] += $row['saldo'];
                    break;
                case 'Patrimonio':
                    $balances['patrimonio'][] = $data;
                    $balances['total_patrimonio'] += $row['saldo'];
                    break;
            }
        }

        return $balances;
    }

    public function getEstadoResultados($empresaId, $fechaDesde = null, $fechaHasta = null)
    {
        $cuentas = CuentasContables::where('empresa_id', $empresaId)
            ->with('parent') // importante para obtener jerarquía
            ->get();

        $saldos = $cuentas->map(function ($cuenta) use ($fechaDesde, $fechaHasta) {
            $saldoData = ComprobanteDetalles::where('cuenta_contable_id', $cuenta->id_cuenta)
                ->whereHas('comprobante', function ($q) use ($fechaDesde, $fechaHasta) {
                    if ($fechaDesde) $q->whereDate('fecha', '>=', $fechaDesde);
                    if ($fechaHasta) $q->whereDate('fecha', '<=', $fechaHasta);
                })
                ->selectRaw('SUM(debe_bs) as total_debe, SUM(haber_bs) as total_haber')
                ->first();

            $totalDebe = $saldoData->total_debe ?? 0;
            $totalHaber = $saldoData->total_haber ?? 0;

            if ($cuenta->tipo_cuenta === 'Ingreso' || str_starts_with($cuenta->codigo_cuenta, '4')) {
                $saldo = $totalHaber - $totalDebe;
            } elseif ($cuenta->tipo_cuenta === 'Egreso' || str_starts_with($cuenta->codigo_cuenta, '5')) {
                $saldo = $totalDebe - $totalHaber;
            } else {
                $saldo = 0;
            }

            // Construir full_parent_chain y level
            $parentChain = [];
            $current = $cuenta;
            while ($current->parent) {
                $parentChain[] = [
                    'codigo_cuenta' => $current->parent->codigo_cuenta,
                    'nombre_cuenta' => $current->parent->nombre_cuenta,
                ];
                $current = $current->parent;
            }

            return [
                'cuenta' => $cuenta,
                'codigo_cuenta' => $cuenta->codigo_cuenta,
                'nombre' => $cuenta->nombre_cuenta,
                'tipo_cuenta' => $cuenta->tipo_cuenta,
                'saldo' => $saldo,
                'full_parent_chain' => array_reverse($parentChain),
                'level' => count($parentChain),
            ];
        });

        // Inicializar resultados
        $resultados = [
            'ingresos' => [],
            'total_ingresos' => 0,
            'egresos' => [],
            'total_egresos' => 0,
            'resultado_neto' => 0,
        ];

        foreach ($saldos as $row) {
            $data = [
                'codigo_cuenta' => $row['codigo_cuenta'],
                'nombre' => $row['nombre'],
                'saldo' => $row['saldo'],
                'full_parent_chain' => $row['full_parent_chain'],
                'level' => $row['level'],
            ];

            if ($row['tipo_cuenta'] === 'Ingreso' || str_starts_with($row['codigo_cuenta'], '4')) {
                $resultados['ingresos'][] = $data;
                $resultados['total_ingresos'] += $row['saldo'];
            } elseif ($row['tipo_cuenta'] === 'Egreso' || str_starts_with($row['codigo_cuenta'], '5')) {
                $resultados['egresos'][] = $data;
                $resultados['total_egresos'] += $row['saldo'];
            }
        }

        $resultados['resultado_neto'] = $resultados['total_ingresos'] - $resultados['total_egresos'];

        return $resultados;
    }
}
