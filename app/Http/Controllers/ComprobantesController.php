<?php

namespace App\Http\Controllers;

use App\Models\Comprobante;
use App\Models\Comprobantes;
use App\Models\CuentasContables;
use App\Models\DetalleComprobantes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use function Pest\Laravel\get;

class ComprobantesController extends Controller
{
    // Muestra todos los comprobantes
    // public function index(Request $request)
    // {
    //     $query = Comprobantes::with('detalles');

    //     if ($request->has('fecha')) {
    //         $query->where('fecha', $request->input('fecha'));
    //     }

    //     if ($request->has('tipo_comprobante')) {
    //         $query->where('tipo_comprobante', $request->input('tipo_comprobante'));
    //     }

    //     if ($request->has('glosa_general')) {
    //         $query->where('glosa_general', 'LIKE', '%' . $request->input('glosa_general') . '%');
    //     }

    //     $comprobantes = $query->paginate(10); // Paginación con filtros aplicados
    //     return view('comprobantes.index', compact('comprobantes'));
    // }



    // Muestra un comprobante específico
    // public function show($id)
    // {
    //     // Obtener el comprobante y sus detalles
    //     $comprobante = Comprobantes::with('detalles')->findOrFail($id);
    //     return view('comprobantes.show', compact('comprobante'));
    // }

    public function home()
    {
        $comprobantes = Comprobante::latest()->paginate(15);
        return view('comprobantes.index', compact('comprobantes'));
    }

    // Muestra el formulario para crear un nuevo comprobante
    public function create()
    {
        // Aquí podrías pasar las cuentas o cualquier otro dato necesario
        // $cuentas = CuentasContables::all();
        $cuentas = CuentasContables::where('es_movimiento', true)->get();
        return view('comprobantes.create', compact('cuentas'));
    }

    public function edit($id) {
        return view('comprobantes.edit');
    }

    // Guarda un nuevo comprobante
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fecha' => 'required|date',
            'tipo' => 'required|in:ingreso,egreso,traspaso,ajuste',
            'descripcion' => 'nullable|string',
            'detalles' => 'required|array|min:2',
            'detalles.*.cuenta_id' => 'required|exists:cuentas,id_cuenta',
            'detalles.*.descripcion' => 'nullable|string',
            'detalles.*.debe' => 'required|numeric|min:0',
            'detalles.*.haber' => 'required|numeric|min:0',
        ]);

        $totalDebe = collect($validated['detalles'])->sum('debe');
        $totalHaber = collect($validated['detalles'])->sum('haber');

        if (round($totalDebe, 2) !== round($totalHaber, 2)) {
            return response()->json([
                'message' => 'El comprobante no cuadra. La suma del debe y del haber deben ser iguales.'
            ], 422);
        }

        

        try {
            DB::transaction(function () use ($validated, $totalDebe) {
                $comprobante = Comprobante::create([
                    'fecha' => $validated['fecha'],
                    'tipo' => $validated['tipo'],
                    'descripcion' => $validated['descripcion'] ?? '',
                    'total' => $totalDebe,
                    'user_id' => Auth::id(),
                ]);

                foreach ($validated['detalles'] as $detalle) {
                    $comprobante->detalles()->create([
                        'cuenta_contable_id' => $detalle['cuenta_id'],
                        'descripcion' => $detalle['descripcion'] ?? '',
                        'debe' => $detalle['debe'],
                        'haber' => $detalle['haber'],
                    ]);
                }
            });
            return redirect()->route('show.comprobantes.home')->with('success', 'Comprobante creado correctamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al crear el comprobante: ' . $e->getMessage()])->withInput();
        }
    }

    // Muestra el formulario para editar un comprobante existente
    // public function edit($id)
    // {
    //     // Buscar el comprobante por su ID
    //     $comprobante = Comprobantes::with('detalles')->findOrFail($id);

    //     // Retornar la vista con el comprobante cargado
    //     return view('comprobantes.edit', compact('comprobante'));
    // }


    // Actualiza un comprobante existente
    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'fecha' => 'required|date',
    //         'tipo_comprobante' => 'required|string',
    //         'glosa_general' => 'nullable|string',
    //     ]);

    //     $comprobante = Comprobantes::findOrFail($id);
    //     $comprobante->update([
    //         'fecha' => $request->fecha,
    //         'tipo' => $request->tipo_comprobante,
    //         'glosa' => $request->glosa_general,
    //     ]);

    //     // Eliminar los detalles antiguos si se van a reemplazar
    //     $comprobante->detalles()->delete();

    //     // Si hay detalles nuevos, guardarlos
    //     if ($request->has('detalles')) {
    //         foreach ($request->detalles as $detalle) {
    //             DetalleComprobantes::create([
    //                 'comprobante_id' => $comprobante->id_comprobante,
    //                 'cuenta_id' => $detalle['cuenta_id'],
    //                 'debe' => $detalle['debe'],
    //                 'haber' => $detalle['haber'],
    //                 'detalle' => $detalle['detalle'] ?? null,
    //             ]);
    //         }
    //     }

    //     return redirect()->route('comprobantes.index')->with('success', 'Comprobante actualizado exitosamente.');
    // }

    // Elimina un comprobante
    // public function destroy($id)
    // {
    //     $comprobante = Comprobantes::findOrFail($id);
    //     $comprobante->delete();

    //     return redirect()->route('comprobantes.index')->with('success', 'Comprobante eliminado exitosamente.');
    // }
}
