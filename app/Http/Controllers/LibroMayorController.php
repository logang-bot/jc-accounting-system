<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CuentasContables;
use App\Models\ComprobanteDetalles;
use App\Models\Comprobantes;

use function Spatie\LaravelPdf\Support\pdf;

class LibroMayorController extends Controller
{
    public function index(Request $request)
    {
        $cuentaMode   = $request->boolean('cuenta_mode'); // true = multi, false = single
        $cuentaId     = $request->get('cuenta');
        $cuentaDesde  = $request->get('cuenta_desde');
        $cuentaHasta  = $request->get('cuenta_hasta');
        $saldoTipo    = $request->get('saldo', 'con_saldo');
        $fechaDesde   = $request->get('fecha_desde');
        $fechaHasta   = $request->get('fecha_hasta');

        // dd($cuentaMode);

        $empresaId = session('empresa_id');

        $query = ComprobanteDetalles::with(['comprobante', 'cuenta'])
            ->whereHas('comprobante', function ($q) use ($empresaId) {
                $q->where('empresa_id', $empresaId);
            })
            // 📌 Filtro CUENTA ÚNICA
            ->when($cuentaMode && $cuentaId, function ($q) use ($cuentaId) {
                $q->where('cuenta_contable_id', $cuentaId);
            })
            // 📌 Filtro RANGO DE CUENTAS
            ->when(!$cuentaMode && $cuentaDesde && $cuentaHasta, function ($q) use ($cuentaDesde, $cuentaHasta) {
                $desdeModel = $cuentaDesde ? CuentasContables::find($cuentaDesde) : null;
                $hastaModel = $cuentaHasta ? CuentasContables::find($cuentaHasta) : null;

                $desdeCodigo = $desdeModel?->codigo_cuenta;
                $hastaCodigo = $hastaModel?->codigo_cuenta;

                // ensure numeric order
                $desdeNum = (int) $desdeCodigo;
                $hastaNum = (int) $hastaCodigo;
                if ($desdeNum > $hastaNum) {
                    [$desdeNum, $hastaNum] = [$hastaNum, $desdeNum];
                }

                // NOTE: this uses Postgres cast syntax. Adjust if your DB differs.
                $q->whereHas('cuenta', function ($sub) use ($desdeNum, $hastaNum) {
                    $sub->whereRaw('(codigo_cuenta)::bigint BETWEEN ? AND ?', [$desdeNum, $hastaNum]);
                });

                return;
            })
            ->when($fechaDesde, fn($q) => $q->whereHas('comprobante', fn($sub) => $sub->whereDate('fecha', '>=', $fechaDesde)))
            ->when($fechaHasta, fn($q) => $q->whereHas('comprobante', fn($sub) => $sub->whereDate('fecha', '<=', $fechaHasta)))
            ->join('comprobantes', 'comprobante_detalles.comprobante_id', '=', 'comprobantes.id')
            ->orderBy('cuenta_contable_id')
            ->orderBy('comprobantes.fecha')
            ->orderBy('comprobantes.numero')
            ->select('comprobante_detalles.*');

        $movimientos = $query->get();

        $libroMayor = [];
        foreach ($movimientos as $mov) {
            $cuenta = $mov->cuenta;

            if (!isset($libroMayor[$cuenta->id_cuenta])) {
                $libroMayor[$cuenta->id_cuenta] = [
                    'codigo'      => $cuenta->codigo_cuenta,
                    'nombre'      => $cuenta->nombre_cuenta,
                    'movimientos' => [],
                ];
            }

            $libroMayor[$cuenta->id_cuenta]['movimientos'][] = (object) [
                'debe'        => $mov->debe,
                'haber'       => $mov->haber,
                'descripcion' => $mov->descripcion,
                'comprobante' => $mov->comprobante,
            ];
        }

        if ($saldoTipo === 'con_saldo') {
            $libroMayor = array_filter($libroMayor, function ($cuentaData) {
                $total = collect($cuentaData['movimientos'])->sum(fn($m) => ($m->debe ?? 0) - ($m->haber ?? 0));
                return $total != 0;
            });
        }

        $empresaId = session('empresa_id'); // o como obtienes la empresa activa

        // Si quieres mostrar TODAS las cuentas de la empresa:
        $cuentas = CuentasContables::where('empresa_id', $empresaId)
            ->orderByRaw("
        CASE
            WHEN tipo_cuenta = 'Activo' THEN 1        
            WHEN tipo_cuenta = 'Pasivo' THEN 2
            WHEN tipo_cuenta = 'Patrimonio' THEN 3
            WHEN tipo_cuenta = 'Ingresos' THEN 4
            WHEN tipo_cuenta = 'Egresos' THEN 5
            ELSE 6
        END, 
        codigo_cuenta ASC
    ")->get();

        return view('libroMayor.index', [
            'libroMayor' => $libroMayor,
            'cuentas' => $cuentas,
            'cuentaSeleccionada' => $cuentaId,
            'cuentaMode' => $cuentaMode,
            'cuentaDesde' => $cuentaDesde,
            'cuentaHasta' => $cuentaHasta,
        ]);
    }

    public function generarPDF(Request $request)
    {
        $cuentaMode   = $request->boolean('cuenta_mode'); // true = multi, false = single
        $cuentaId     = $request->get('cuenta');
        $cuentaDesde  = $request->get('cuenta_desde');
        $cuentaHasta  = $request->get('cuenta_hasta');
        $saldoTipo    = $request->get('saldo', 'con_saldo');
        $fechaDesde   = $request->get('fecha_desde');
        $fechaHasta   = $request->get('fecha_hasta');

        $query = ComprobanteDetalles::with(['comprobante', 'cuenta'])
            ->when($cuentaMode && $cuentaId, function ($q) use ($cuentaId) {
                // single mode
                $q->where('cuenta_contable_id', $cuentaId);
            })
            ->when(!$cuentaMode && $cuentaDesde && $cuentaHasta, function ($q) use ($cuentaDesde, $cuentaHasta) {
                $desdeModel = $cuentaDesde ? CuentasContables::find($cuentaDesde) : null;
                $hastaModel = $cuentaHasta ? CuentasContables::find($cuentaHasta) : null;

                $desdeCodigo = $desdeModel?->codigo_cuenta;
                $hastaCodigo = $hastaModel?->codigo_cuenta;

                // ensure numeric order
                $desdeNum = (int) $desdeCodigo;
                $hastaNum = (int) $hastaCodigo;
                if ($desdeNum > $hastaNum) {
                    [$desdeNum, $hastaNum] = [$hastaNum, $desdeNum];
                }

                // NOTE: this uses Postgres cast syntax. Adjust if your DB differs.
                $q->whereHas('cuenta', function ($sub) use ($desdeNum, $hastaNum) {
                    $sub->whereRaw('(codigo_cuenta)::bigint BETWEEN ? AND ?', [$desdeNum, $hastaNum]);
                });

                return;
            })
            ->when($fechaDesde, fn($q) => $q->whereHas('comprobante', fn($sub) => $sub->whereDate('fecha', '>=', $fechaDesde)))
            ->when($fechaHasta, fn($q) => $q->whereHas('comprobante', fn($sub) => $sub->whereDate('fecha', '<=', $fechaHasta)))
            ->join('comprobantes', 'comprobante_detalles.comprobante_id', '=', 'comprobantes.id')
            ->orderBy('cuenta_contable_id')
            ->orderBy('comprobantes.fecha')
            ->orderBy('comprobantes.numero')
            ->select('comprobante_detalles.*');

        $movimientos = $query->get();

        $libroMayor = [];
        foreach ($movimientos as $mov) {
            $cuenta = $mov->cuenta;

            if (!isset($libroMayor[$cuenta->id_cuenta])) {
                $libroMayor[$cuenta->id_cuenta] = [
                    'codigo'      => $cuenta->codigo_cuenta,
                    'nombre'      => $cuenta->nombre_cuenta,
                    'movimientos' => [],
                ];
            }

            $libroMayor[$cuenta->id_cuenta]['movimientos'][] = (object) [
                'debe'        => $mov->debe,
                'haber'       => $mov->haber,
                'descripcion' => $mov->descripcion,
                'comprobante' => $mov->comprobante,
            ];
        }

        if ($saldoTipo === 'con_saldo') {
            $libroMayor = array_filter($libroMayor, function ($cuentaData) {
                $total = collect($cuentaData['movimientos'])->sum(fn($m) => ($m->debe ?? 0) - ($m->haber ?? 0));
                return $total != 0;
            });
        }

        $pdf = pdf()->view('libroMayorPDF', [
            'libroMayor' => $libroMayor,
        ]);

        return $pdf->name('LibroMayor_{$cuenta->codigo_cuenta}.pdf');
    }

    public function varias()
    {
        return view('libroMayor.varias');
    }
}
