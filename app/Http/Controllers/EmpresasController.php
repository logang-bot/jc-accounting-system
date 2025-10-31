<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use Database\Seeders\CuentasSeeder;

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
            'tipo_documento' => 'required|string|max:20',
            'numero_documento' => 'required|string|max:30',
            'direccion' => 'nullable|string|max:255',
            'ciudad' => 'nullable|string|max:100',
            'telefono' => 'nullable|string|max:20',
            'casa_matriz' => 'required|boolean',
            'periodo' => 'nullable|string|max:100',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',

        ]);

        $empresa = Empresa::create([
            'name' => $request->name,
            'tipo_documento' => $request->tipo_documento,
            'numero_documento' => $request->documento,
            'direccion' => $request->direccion,
            'ciudad' => $request->ciudad,
            'telefono' => $request->telefono,
            'casa_matriz' => $request->casa_matriz,
            'periodo' => $request->periodo,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
        ]);

        CuentasSeeder::crearCuentasActivos($empresa);
        CuentasSeeder::crearCuentasPasivos($empresa);
        CuentasSeeder::crearCuentasPatrimonio($empresa);
        CuentasSeeder::crearCuentasIngresos($empresa);
        CuentasSeeder::crearCuentasEgresos($empresa);

        return redirect()->route('show.empresas.home')->with('success', 'Empresa creada correctamente.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'tipo_documento' => 'required|string|max:20',
            'numero_documento' => 'required|string|max:30',
            'direccion' => 'nullable|string|max:255',
            'ciudad' => 'nullable|string|max:100',
            'telefono' => 'nullable|string|max:20',
            'casa_matriz' => 'required|boolean',
            'periodo' => 'nullable|string|max:100',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
        ]);

        $empresa = Empresa::findOrFail($id);

        $empresa->update([
            'name' => $request->name,
            'tipo_documento' => $request->tipo_documento,
            'numero_documento' => $request->documento,
            'direccion' => $request->direccion,
            'ciudad' => $request->ciudad,
            'telefono' => $request->telefono,
            'casa_matriz' => $request->casa_matriz,
            'periodo' => $request->periodo,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,

        ]);

        return redirect()->route('show.empresas.detail', $id)->with('success', 'Datos actualizados correctamente.');
    }

    public function destroy($id)
    {
        Empresa::findOrFail($id)->delete();
        return redirect()->route('show.empresas.home')->with('success', 'Empresa eliminada correctamente.');
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
