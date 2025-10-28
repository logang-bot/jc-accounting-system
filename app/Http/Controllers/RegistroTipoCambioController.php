<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistroTipoCambio;

class RegistroTipoCambioController extends Controller
{
    public function index()
    {
        $registros = RegistroTipoCambio::orderBy('fecha', 'desc')->get();


        $fecha = date('d/m/Y');
        $url = "https://www.bcb.gob.bo/calculadora-ufv/frmCargaValores.php?txtFecha=" . urlencode($fecha) . "&txtFechaFin=&txtMonto=&txtCalcula=";

        $ufvHtml = file_get_contents($url);

        $valorUfv = $ufvHtml ?? 0.00;

        return view('tipoCambio.index', compact('registros', 'valorUfv'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fecha' => 'required|date|unique:registro_tipo_cambios,fecha',
            'valor_ufv' => 'required|numeric|min:0',
            'valor_sus' => 'required|numeric|min:0',
        ]);

        RegistroTipoCambio::create($validated);

        return redirect()->route('tipoCambio.index')
            ->with('success', 'Registro de tipo de cambio creado correctamente.');
    }
}
