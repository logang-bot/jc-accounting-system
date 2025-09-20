<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CuentasContables;
use App\Models\ComprobanteDetalles;
use App\Models\Comprobantes;
use Barryvdh\DomPDF\Facade\Pdf; // Correcto: fuera de la clase

class LibroMayorController extends Controller
{
    public function index(Request $request)
    {
        $cuentaId     = $request->get('cuenta');
        $nombreCuenta = $request->get('nombre_cuenta');
        $proyectoId   = $request->get('proyecto');
        $moneda       = $request->get('moneda', 'bs');
        $saldoTipo    = $request->get('saldo', 'con_saldo');
        $fechaDesde   = $request->get('fecha_desde');
        $fechaHasta   = $request->get('fecha_hasta');

        $query = ComprobanteDetalles::with(['comprobante', 'cuenta'])
            ->when($cuentaId, fn($q) => $q->where('cuenta_contable_id', $cuentaId))
            ->when($nombreCuenta, fn($q) => $q->whereHas('cuenta', fn($sub) => $sub->where('nombre_cuenta', 'ilike', "%$nombreCuenta%")))
            ->when($proyectoId, fn($q) => $q->where('proyecto_id', $proyectoId))
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

            $debe  = $moneda === 'usd' ? ($mov->debe / $mov->comprobante->tasa_cambio) : $mov->debe;
            $haber = $moneda === 'usd' ? ($mov->haber / $mov->comprobante->tasa_cambio) : $mov->haber;

            $libroMayor[$cuenta->id_cuenta]['movimientos'][] = (object) [
                'debe'        => $debe,
                'haber'       => $haber,
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

        $cuentas   = CuentasContables::all();
        $proyectos = []; // si luego creas el modelo Proyectos, aquí lo traes

        return view('libroMayor.index', [
            'libroMayor' => $libroMayor,
            'cuentas' => $cuentas,
            'proyectos' => $proyectos,
            'cuentaSeleccionada' => $cuentaId, // <-- Variable para Blade
        ]);
    }

    public function generarPDF(Request $request)
    {
        $cuentaId   = $request->get('cuenta');
        $moneda     = $request->get('moneda', 'bs');
        $fechaDesde = $request->get('fecha_desde');
        $fechaHasta = $request->get('fecha_hasta');

        if (!$cuentaId) {
            return redirect()->back()->with('error', 'Debe seleccionar una cuenta.');
        }

        $movimientos = ComprobanteDetalles::with(['comprobante', 'cuenta'])
            ->where('cuenta_contable_id', $cuentaId)
            ->when($fechaDesde, fn($q) => $q->whereHas('comprobante', fn($sub) => $sub->whereDate('fecha', '>=', $fechaDesde)))
            ->when($fechaHasta, fn($q) => $q->whereHas('comprobante', fn($sub) => $sub->whereDate('fecha', '<=', $fechaHasta)))
            ->join('comprobantes', 'comprobante_detalles.comprobante_id', '=', 'comprobantes.id')
            ->orderBy('comprobante_detalles.id')
            ->select('comprobante_detalles.*')
            ->get();

        $libroMayor = [];
        $saldoAcumulado = 0;
        foreach ($movimientos as $mov) {
            $debe  = $moneda === 'usd' ? ($mov->debe / $mov->comprobante->tasa_cambio) : $mov->debe;
            $haber = $moneda === 'usd' ? ($mov->haber / $mov->comprobante->tasa_cambio) : $mov->haber;

            $saldoAcumulado += $debe - $haber;

            $libroMayor[] = [
                'fecha'        => $mov->comprobante->fecha,
                'comprobante'  => $mov->comprobante->numero,
                'descripcion'  => $mov->descripcion,
                'debe'         => $debe,
                'haber'        => $haber,
                'saldo'        => $saldoAcumulado,
            ];
        }

        $cuenta = CuentasContables::find($cuentaId);

        $pdf = Pdf::loadView('libroMayorPDF', compact('libroMayor', 'cuenta'));
        return $pdf->stream("LibroMayor_{$cuenta->codigo_cuenta}.pdf");
    }

    public function varias()
    {
        return view('libroMayor.varias'); // Apunta a tu Blade 'varias.blade.php'
    }
}
