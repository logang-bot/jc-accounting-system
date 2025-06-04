<?php

namespace App\Http\Controllers;

use App\Models\Comprobante;
use App\Models\Comprobantes;
use App\Models\CuentasContables;
use App\Models\DetalleComprobantes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ComprobantesController extends Controller
{
    public function home(Request $request)
    {
        $query = Comprobante::query();

        // Filtro por fecha exacta
        if ($request->filled('fecha')) {
            $query->whereDate('fecha', $request->fecha);
        }

        // Filtro por tipo de comprobante
        if ($request->filled('tipo_comprobante')) {
            $query->where('tipo', 'ILIKE', '%' . $request->tipo_comprobante . '%');
        }

        // Filtro por glosa / descripción
        if ($request->filled('glosa_general')) {
            $query->where('descripcion', 'ILIKE', '%' . $request->glosa_general . '%');
        }

        $comprobantes = $query->latest()->paginate(15)->appends($request->query());

        return view('comprobantes.index', compact('comprobantes'));
    }

    // Muestra el formulario para crear un nuevo comprobante
    public function create()
    {
        // Aquí podrías pasar las cuentas o cualquier otro dato necesario
        // $cuentas = CuentasContables::all();
        $cuentas = CuentasContables::where('es_movimiento', true)->get();
        return view('comprobantes.create', [
            'editMode' => false,
            'cuentas' => $cuentas,
            'comprobante' => null // or new Comprobante if you prefer
        ]);
    }

    public function show($id)
    {
        $comprobante = Comprobante::with(['detalles.cuenta', 'user'])->findOrFail($id);
        return view('comprobantes.show', compact('comprobante'));
    }

    public function edit($id) {
        $cuentas = CuentasContables::where('es_movimiento', true)->get();
        $comprobante = Comprobante::with('detalles')->findOrFail($id);
        return view('comprobantes.create', [
            'editMode' => true,
            'comprobante' => $comprobante,
            'cuentas' => $cuentas
        ]);
    }

    // Guarda un nuevo comprobante
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fecha' => 'required|date',
            'tipo' => 'required|in:ingreso,egreso,traspaso,ajuste',
            'descripcion' => 'nullable|string',
            'tasa_cambio' => 'required|numeric|min:0.0001',
            'detalles' => 'required|array|min:2',
            'detalles.*.cuenta_id' => 'required|exists:cuentas,id_cuenta',
            'detalles.*.descripcion' => 'nullable|string',
            'detalles.*.debe' => 'required|numeric|min:0',
            'detalles.*.haber' => 'required|numeric|min:0',
        ]);

        $validator->after(function ($validator) use ($request) {
            $totalDebe = 0;
            $totalHaber = 0;

            foreach ($request->input('detalles', []) as $detalle) {
                $totalDebe += floatval($detalle['debe'] ?? 0);
                $totalHaber += floatval($detalle['haber'] ?? 0);
            }

            if (round($totalDebe, 2) !== round($totalHaber, 2)) {
                $validator->errors()->add('detalles', 'El comprobante no cuadra. La suma del debe y del haber deben ser iguales.');
            }
        });

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        try {
            DB::transaction(function () use ($validated) {
                $totalDebe = collect($validated['detalles'])->sum('debe');

                $comprobante = Comprobante::create([
                    'fecha' => $validated['fecha'],
                    'tipo' => $validated['tipo'],
                    'descripcion' => $validated['descripcion'] ?? '',
                    'total' => $totalDebe,
                    'tasa_cambio' => $validated['tasa_cambio'],
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

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'fecha' => 'required|date',
            'tipo' => 'required|string|in:ingreso,egreso,traspaso,ajuste',
            'descripcion' => 'nullable|string',
            'tasa_cambio' => 'required|numeric|min:0.0001',
            'detalles' => 'required|array|min:1',
            'detalles.*.cuenta_id' => 'required|exists:cuentas,id_cuenta',
            'detalles.*.descripcion' => 'nullable|string',
            'detalles.*.debe' => 'nullable|numeric|min:0',
            'detalles.*.haber' => 'nullable|numeric|min:0',
        ]);

        $validator->after(function ($validator) use ($request) {
            $totalDebe = 0;
            $totalHaber = 0;

            foreach ($request->input('detalles', []) as $detalle) {
                $debe = is_numeric($detalle['debe']) ? floatval($detalle['debe']) : 0;
                $haber = is_numeric($detalle['haber']) ? floatval($detalle['haber']) : 0;
                $totalDebe += $debe;
                $totalHaber += $haber;
            }

            if (round($totalDebe, 2) !== round($totalHaber, 2)) {
                $validator->errors()->add('detalles', 'El comprobante no cuadra. La suma del debe y del haber deben ser iguales.');
            }
        });

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::transaction(function () use ($request, $id) {
            $comprobante = Comprobante::findOrFail($id);

            $comprobante->update([
                'fecha' => $request->fecha,
                'tipo' => $request->tipo,
                'descripcion' => $request->descripcion,
                'tasa_cambio' => $request['tasa_cambio'],
            ]);

            // Remove existing detalles and recreate them
            $comprobante->detalles()->delete();

            foreach ($request->detalles as $detalle) {
                $comprobante->detalles()->create([
                    'cuenta_contable_id' => $detalle['cuenta_id'],
                    'descripcion' => $detalle['descripcion'] ?? null,
                    'debe' => $detalle['debe'] ?? 0,
                    'haber' => $detalle['haber'] ?? 0,
                ]);
            }
        });

        return redirect()->route('show.comprobantes.home')->with('success', 'Comprobante actualizado correctamente.');
    }

    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $comprobante = Comprobante::findOrFail($id);
                $comprobante->detalles()->delete(); // Elimina detalles primero
                $comprobante->delete(); // Luego el comprobante
            });

            return redirect()->route('show.comprobantes.home')->with('success', 'Comprobante eliminado correctamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al eliminar el comprobante: ' . $e->getMessage()]);
        }
    }
}
