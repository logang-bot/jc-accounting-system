<?php

namespace App\Http\Controllers;

use App\Models\CuentasContables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Empresa;
use App\Models\User;
use Database\Seeders\CuentasSeeder;
use Spatie\Permission\Models\Role;

class EmpresasController extends Controller
{
    public function home()
    {
        $empresas = Empresa::all();
        return view('empresas.index', [
            'empresas' => $empresas,
        ]);
    }

    public function create()
    {
        return view('empresas.create');
    }

    public function show($id)
    {
        session(['empresa_id' => $id]);
        $empresa = Empresa::find($id);
        return view('empresas.show', compact('empresa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nit_ci' => 'nullable|string|max:20',
            'casa_matriz' => 'required|boolean',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'periodo' => 'nullable|string|max:100',
        ]);

        $empresa = Empresa::create([
            'name' => $request->name,
            'nit_ci' => $request->nit_ci,
            'casa_matriz' => $request->casa_matriz,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'periodo' => $request->periodo,
        ]);

        CuentasSeeder::crearCuentasActivos($empresa);
        CuentasSeeder::crearCuentasPasivos($empresa);
        CuentasSeeder::crearCuentasPatrimonio($empresa);
        CuentasSeeder::crearCuentasIngresos($empresa);
        CuentasSeeder::crearCuentasEgresos($empresa);

        return redirect()->route('show.empresas.create')->with('success', 'Empresa creada correctamente.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nit_ci' => 'nullable|string|max:20',
            'casa_matriz' => 'required|boolean',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'periodo' => 'nullable|string|max:100',
        ]);

        $empresa = Empresa::findOrFail($id);

        $empresa->update([
            'name' => $request->name,
            'nit_ci' => $request->nit_ci,
            'casa_matriz' => $request->casa_matriz,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'periodo' => $request->periodo,
        ]);

        return redirect()->route('show.empresas.detail', $id)->with('success', 'Datos actualizados correctamente.');
    }

    public function destroy($id)
    {
        Empresa::findOrFail($id)->delete();
        return redirect()->route('show.empresas.create')->with('success', 'Empresa eliminada correctamente.');
    }


    public function archive($id)
    {
        $empresa = Empresa::findOrFail($id);
        $empresa->activa = !$empresa->activa;
        $empresa->save();

        return redirect()->back()->with('status', $empresa->activa ? 'Empresa activada' : 'Empresa archivada');
    }

    public function exit()
    {
        session()->forget('empresa_id');
        return redirect('/empresas/crear');
    }
}
