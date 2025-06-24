<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;

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

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nit' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'ciudad' => 'nullable|string|max:100',
            'provincia' => 'nullable|string|max:100',
            'telefono' => 'nullable|string|max:20',
            'celular' => 'nullable|string|max:20',
            'correo_electronico' => 'nullable|email|max:255',
            'periodo' => 'required|in:Mineria,Comercial,Agropecuaria,Industrial',
            'gestion' => 'required|integer|min:1900|max:' . (now()->year + 1),
        ]);

        Empresa::create([
            'name' => $request->name,
            'nit' => $request->nit,
            'direccion' => $request->direccion,
            'ciudad' => $request->ciudad,
            'provincia' => $request->provincia,
            'telefono' => $request->telefono,
            'celular' => $request->celular,
            'correo_electronico' => $request->correo_electronico,
            'periodo' => $request->periodo,
            'gestion' => $request->gestion,
        ]);

        return redirect()->route('show.empresas.create')->with('success', 'Empresa creada correctamente.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nit' => 'nullable|string|max:255',
            'direccion' => 'nullable|string|max:255',
            'ciudad' => 'nullable|string|max:255',
            'provincia' => 'nullable|string|max:100',
            'telefono' => 'nullable|string|max:255',
            'celular' => 'nullable|string|max:20',
            'correo_electronico' => 'nullable|email|max:100',
            'periodo' => 'required|in:Mineria,Comercial,Agropecuaria,Industrial',
            'gestion' => 'required|integer|min:1900|max:' . (now()->year + 1),
        ]);

        $empresa = Empresa::findOrFail($id);

        $empresa->update([
            'name' => $request->name,
            'nit' => $request->nit,
            'direccion' => $request->direccion,
            'ciudad' => $request->ciudad,
            'provincia' => $request->provincia,
            'telefono' => $request->telefono,
            'celular' => $request->celular,
            'correo_electronico' => $request->correo_electronico,
            'periodo' => $request->periodo,
            'gestion' => $request->gestion,
        ]);

        return redirect()->route('show.empresas.home', $id)->with('success', 'Datos actualizados correctamente.');
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
