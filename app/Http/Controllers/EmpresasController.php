<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\DatoEmpresa;

class EmpresasController extends Controller
{
    public function home($id)
    {
        session(['empresa_id' => $id]);
        $empresa = Empresa::find($id);
        return view('empresas.dashboard', compact('empresa'));
    }

    public function create()
    {
        return view('empresas.create', ['empresas' => Empresa::all()]);
    }
    
    public function show($id)
    {
        session(['empresa_id' => $id]);
        $empresa = Empresa::with('datoEmpresa')->findOrFail($id);
        return view('empresas.company', compact('empresa'));
    }
    
    public function edit($id)
    {
        session(['empresa_id' => $id]);
        $empresa = Empresa::with('datoEmpresa')->findOrFail($id);
        return view('empresas.company', compact('empresa'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $empresa = Empresa::create(['name' => $request->name]);

        DatoEmpresa::create([
            'empresa_id' => $empresa->id,
            'nit' => '',
            'direccion' => '',
            'ciudad' => '',
            'telefono' => '',
            'periodo' => 'Enero - Diciembre (Comercial)',
            'gestion' => now()->year,
        ]);

        return redirect()->route('show.empresas.create')->with('success', 'Empresa creada correctamente.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nit' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'ciudad' => 'required|string|max:255',
            'provincia' => 'nullable|string|max:100',
            'telefono' => 'required|string|max:255',
            'celular' => 'nullable|string|max:20',
            'correo_electronico' => 'nullable|email|max:100',
            'periodo' => 'required|string|max:255',
            'gestion' => 'required|string|max:255',
        ]);

        // Actualizar el nombre de la empresa
        Empresa::findOrFail($id)->update(['name' => $request->name]);

        // Actualizar o crear los datos de la empresa
        DatoEmpresa::updateOrCreate(
            ['empresa_id' => $id],
            $request->only(['nit', 'direccion', 'ciudad', 'provincia', 'telefono', 'celular', 'correo_electronico', 'periodo', 'gestion'])
        );

        return redirect()->route('empresa.show', $id)->with('success', 'Datos actualizados correctamente.');
    }

    public function destroy($id)
    {
        Empresa::findOrFail($id)->delete();
        return redirect()->route('show.empresas.create')->with('success', 'Empresa eliminada correctamente.');
    }

    public function exit()
    {
        session()->forget('empresa_id');
        return redirect('/empresas/crear');
    }

    // public function index()
    // {
    //     $empresa_id = session('empresa_id');

    //     if (!$empresa_id) {
    //         return redirect()->route('empresas')->with('error', 'Debe seleccionar una empresa primero.');
    //     }

    //     $empresa = Empresa::with('datoEmpresa')->findOrFail($empresa_id);
    //     return view('empresas.company', compact('empresa'));
    // }

}
