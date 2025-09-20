<?php

namespace App\Http\Controllers;

use App\Services\LibroMayorService;
use Illuminate\Http\Request;

class LibroMayorController extends Controller
{
    protected $libroMayor;

    public function __construct(LibroMayorService $libroMayor)
    {
        $this->libroMayor = $libroMayor;
    }

    public function index(Request $request)
    {
        $empresaId = session('empresa_id');
        $fechaDesde = $request->input('fecha_desde');
        $fechaHasta = $request->input('fecha_hasta');
        $cuentaId   = $request->input('cuenta_id');

        $cuentas = $cuentaId ? [$cuentaId] : null;

        $libroMayor = $this->libroMayor->generate($empresaId, $fechaDesde, $fechaHasta, $cuentas);

        return view('libroMayor.index', compact('libroMayor', 'fechaDesde', 'fechaHasta'));
    }
}
