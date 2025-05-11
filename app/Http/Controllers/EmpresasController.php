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
        $empresa = Empresa::findOrFail($id);
        return view('home', compact('empresa'));
    }

    public function index()
    {
        return view('empresas.create', ['empresas' => Empresa::all()]);
    }

    public function create()
    {
        return view('empresas.create', ['empresas' => Empresa::all()]);
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

        return redirect()->route('empresas.create')->with('success', 'Empresa creada correctamente.');
    }

    public function destroy($id)
    {
        Empresa::findOrFail($id)->delete();
        return redirect()->route('empresas.create')->with('success', 'Empresa eliminada correctamente.');
    }

    public function show($id)
    {
        return view('empresas.company', ['empresa' => Empresa::with('datoEmpresa')->findOrFail($id)]);
    }

    public function ingresarEmpresa($id)
    {
        session(['empresa_id' => $id]);
        return redirect()->route('empresa.show', ['id' => $id]);
    }

    public function salir()
    {
        session()->forget('empresa_id');
        return redirect('/empresas/crear');
    }
}


/*
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;

class EmpresasController extends Controller
{
    public function home($id)
    {
        // Encuentra la empresa por su ID
        $empresa = Empresa::findOrFail($id);

        // Pasa la información de la empresa a la vista 'empresa.home'
        return view('home', compact('empresa'));
    }

    public function index()
    {
        $empresas = Empresa::all();
        return view('empresas.create', compact('empresas'));
    }

    public function create()
    {
        $empresas = Empresa::all();
        return view('empresas.create', compact('empresas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Empresa::create(['name' => $request->name]);

        return redirect()->route('empresas.create')->with('success', 'Empresa creada correctamente');
    }

    public function destroy($id)
    {
        $empresa = Empresa::findOrFail($id);
        $empresa->delete();

        return redirect()->route('empresas.create')->with('success', 'Empresa eliminada correctamente');
    }

    public function show($id)
    {
        $empresa = Empresa::findOrFail($id);
        return view('company', compact('empresa'));
    }

    public function verEmpresa($id)
    {
        // Encuentra la empresa por su ID
        $empresa = Empresa::findOrFail($id);

        // Pasa la información de la empresa a la vista 'show'
        return view('empresas.company', compact('empresa'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nit' => 'required|string|max:20',
            'direccion' => 'required|string|max:255',
            'ciudad' => 'required|string|max:100',
            'telefono' => 'required|string|max:20',
        ]);

        $empresa = Empresa::findOrFail($id);
        $empresa->update($request->all());

        return redirect()->route('empresa.ver', $id)->with('success', 'Empresa actualizada correctamente');
    }

    public function salir(Request $request)
    {
        // Eliminar la sesión de la empresa seleccionada
        session()->forget('empresa_id');

        // Redirigir a la página /empresas/crear
        return redirect('/empresas/crear');
    }
}
*/