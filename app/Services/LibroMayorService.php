<?php

namespace App\Services;

use App\Models\ComprobanteDetalles;
use App\Models\CuentasContables;
use Illuminate\Support\Facades\DB;

class LibroMayorService
{
    /**
     * Generate Libro Mayor report structure.
     *
     * @param int $empresaId
     * @param string|null $fechaDesde
     * @param string|null $fechaHasta
     * @param array|null $cuentasIds
     * @return array
     */
    // public function generate(int $empresaId, ?string $fechaDesde, ?string $fechaHasta, ?array $cuentasIds = null): array
    // {
    //     $aperturas = ComprobanteDetalles::forEmpresa($empresaId)
    //         ->when($fechaDesde, fn($q) => $q->whereHas('comprobante', fn($c) => $c->whereDate('fecha', '<', $fechaDesde)))
    //         ->when($cuentasIds, fn($q) => $q->forCuentas($cuentasIds))
    //         ->select('cuenta_contable_id', DB::raw('SUM(debe - haber) as apertura'))
    //         ->groupBy('cuenta_contable_id')
    //         ->pluck('apertura', 'cuenta_contable_id')
    //         ->toArray();

    //     $movimientos = ComprobanteDetalles::forEmpresa($empresaId)
    //         ->betweenFechas($fechaDesde, $fechaHasta)
    //         ->when($cuentasIds, fn($q) => $q->forCuentas($cuentasIds))
    //         ->with(['comprobante', 'cuenta'])
    //         ->orderBy('cuenta_contable_id')
    //         ->orderBy(DB::raw('(SELECT fecha FROM comprobantes WHERE comprobantes.id_comprobante = comprobante_detalles.comprobante_id)'))
    //         ->get()
    //         ->groupBy('cuenta_contable_id');

    //     $result = [];
    //     foreach ($movimientos as $cuentaId => $rows) {
    //         $cuenta = $rows->first()->cuenta ?? CuentasContables::find($cuentaId);
    //         $apertura = $aperturas[$cuentaId] ?? 0;
    //         $saldo = $apertura;
    //         $totalDebe = 0;
    //         $totalHaber = 0;
    //         $movs = [];

    //         foreach ($rows as $m) {
    //             $saldo += $m->debe - $m->haber;
    //             $movs[] = [
    //                 'fecha' => $m->comprobante->fecha,
    //                 'numero' => $m->comprobante->numero,
    //                 'glosa' => $m->comprobante->glosa,
    //                 'debe' => $m->debe,
    //                 'haber' => $m->haber,
    //                 'saldo' => $saldo,
    //             ];
    //             $totalDebe += $m->debe;
    //             $totalHaber += $m->haber;
    //         }

    //         $result[] = [
    //             'cuenta_id' => $cuentaId,
    //             'codigo' => $cuenta->codigo_cuenta,
    //             'nombre' => $cuenta->nombre,
    //             'apertura' => $apertura,
    //             'movimientos' => $movs,
    //             'total_debe' => $totalDebe,
    //             'total_haber' => $totalHaber,
    //             'saldo_final' => $saldo,
    //         ];
    //     }

    //     return $result;
    // }

    public function generate($empresaId, $fechaDesde = null, $fechaHasta = null, $cuentas = null)
    {
        $query = ComprobanteDetalles::with(['comprobante', 'cuenta'])
            ->whereHas('comprobante', function ($q) use ($empresaId, $fechaDesde, $fechaHasta) {
                $q->where('empresa_id', $empresaId);

                if ($fechaDesde) {
                    $q->whereDate('fecha', '>=', $fechaDesde);
                }
                if ($fechaHasta) {
                    $q->whereDate('fecha', '<=', $fechaHasta);
                }
            });

        if ($cuentas) {
            $query->whereIn('cuenta_contable_id', $cuentas);
        }

        $detalles = $query->orderBy('cuenta_contable_id')
                          ->orderBy('comprobante_id')
                          ->get();

        // ✅ Transform into structure expected by Blade
        $libro = [];

        foreach ($detalles as $detalle) {
            $cuentaId = $detalle->cuenta->id_cuenta;

            if (!isset($libro[$cuentaId])) {
                $libro[$cuentaId] = [
                    'codigo' => $detalle->cuenta->codigo_cuenta,
                    'nombre' => $detalle->cuenta->nombre_cuenta,
                    'movimientos' => [],
                    'totales' => ['debe' => 0, 'haber' => 0],
                ];
            }

            $libro[$cuentaId]['movimientos'][] = $detalle;

            $libro[$cuentaId]['totales']['debe'] += $detalle->debe;
            $libro[$cuentaId]['totales']['haber'] += $detalle->haber;
        }

        return $libro;
    }
}
