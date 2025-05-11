<?php

namespace App\Http\Controllers;

use App\Models\Comprobantes;
use App\Models\DetalleComprobantes;
use Illuminate\Http\Request;

class ComprobantesController extends Controller
{
    // Muestra todos los comprobantes
    public function index(Request $request)
    {
        $query = Comprobantes::with('detalles');

        if ($request->has('fecha')) {
            $query->where('fecha', $request->input('fecha'));
        }

        if ($request->has('tipo_comprobante')) {
            $query->where('tipo_comprobante', $request->input('tipo_comprobante'));
        }

        if ($request->has('glosa_general')) {
            $query->where('glosa_general', 'LIKE', '%' . $request->input('glosa_general') . '%');
        }

        $comprobantes = $query->paginate(10); // Paginación con filtros aplicados
        return view('comprobantes.index', compact('comprobantes'));
    }



    // Muestra un comprobante específico
    public function show($id)
    {
        // Obtener el comprobante y sus detalles
        $comprobante = Comprobantes::with('detalles')->findOrFail($id);
        return view('comprobantes.show', compact('comprobante'));
    }

    // Muestra el formulario para crear un nuevo comprobante
    public function create()
    {
        // Aquí podrías pasar las cuentas o cualquier otro dato necesario
        // $cuentas = CuentasContables::all();
        return view('comprobantes.crud.create');
    }

    // Guarda un nuevo comprobante
    public function store(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'tipo_comprobante' => 'required|string',
            'glosa_general' => 'nullable|string',
        ]);

        // Crear el comprobante
        $comprobante = Comprobantes::create([
            'fecha' => $request->fecha,
            'tipo' => $request->tipo_comprobante,
            'glosa' => $request->glosa_general,
        ]);

        // Ahora, si hay detalles, los guardamos
        if ($request->has('detalles')) {
            foreach ($request->detalles as $detalle) {
                DetalleComprobantes::create([
                    'comprobante_id' => $comprobante->id_comprobante,
                    'cuenta_id' => $detalle['cuenta_id'],
                    'debe' => $detalle['debe'],
                    'haber' => $detalle['haber'],
                    'detalle' => $detalle['detalle'] ?? null,
                ]);
            }
        }

        return redirect()->route('comprobantes.index')->with('success', 'Comprobante creado exitosamente.');
    }

    // Muestra el formulario para editar un comprobante existente
    public function edit($id)
    {
        // Buscar el comprobante por su ID
        $comprobante = Comprobantes::with('detalles')->findOrFail($id);

        // Retornar la vista con el comprobante cargado
        return view('comprobantes.edit', compact('comprobante'));
    }


    // Actualiza un comprobante existente
    public function update(Request $request, $id)
    {
        $request->validate([
            'fecha' => 'required|date',
            'tipo_comprobante' => 'required|string',
            'glosa_general' => 'nullable|string',
        ]);

        $comprobante = Comprobantes::findOrFail($id);
        $comprobante->update([
            'fecha' => $request->fecha,
            'tipo' => $request->tipo_comprobante,
            'glosa' => $request->glosa_general,
        ]);

        // Eliminar los detalles antiguos si se van a reemplazar
        $comprobante->detalles()->delete();

        // Si hay detalles nuevos, guardarlos
        if ($request->has('detalles')) {
            foreach ($request->detalles as $detalle) {
                DetalleComprobantes::create([
                    'comprobante_id' => $comprobante->id_comprobante,
                    'cuenta_id' => $detalle['cuenta_id'],
                    'debe' => $detalle['debe'],
                    'haber' => $detalle['haber'],
                    'detalle' => $detalle['detalle'] ?? null,
                ]);
            }
        }

        return redirect()->route('comprobantes.index')->with('success', 'Comprobante actualizado exitosamente.');
    }

    // Elimina un comprobante
    public function destroy($id)
    {
        $comprobante = Comprobantes::findOrFail($id);
        $comprobante->delete();

        return redirect()->route('comprobantes.index')->with('success', 'Comprobante eliminado exitosamente.');
    }
}
