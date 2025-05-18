<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CuentasContables;

class CuentaController extends Controller
{
    // Mostrar todas las cuentas
    public function home()
    {
        // Obtener todas las cuentas principales con sus subcuentas
        $cuentas = CuentasContables::whereNull('parent_id') // Solo las cuentas principales
            ->where('estado', true)
            ->with(['children' => function ($query) {
                $query->where('estado', true)
                    ->orderBy('codigo_cuenta', 'asc'); // Ordenar subcuentas por código
            }])
            ->orderByRaw("
                CASE
                    WHEN tipo_cuenta = 'Activo' THEN 1        
                    WHEN tipo_cuenta = 'Pasivo' THEN 2
                    WHEN tipo_cuenta = 'Patrimonio' THEN 3
                    WHEN tipo_cuenta = 'Ingresos' THEN 4
                    WHEN tipo_cuenta = 'Egresos' THEN 5
                    ELSE 6
                END, 
                codigo_cuenta ASC
            ")
            ->get();

        // Asignar niveles a las cuentas
        // foreach ($cuentas as $cuenta) {
        //     $cuenta->nivel = $this->determinarNivelPorCodigo($cuenta->codigo_cuenta);

        //     // Asignar niveles a las subcuentas
        //     foreach ($cuenta->children as $subcuenta) {
        //         $subcuenta->nivel = $this->determinarNivelPorCodigo($subcuenta->codigo_cuenta);
        //     }
        // }
// echo("<script>console.log('PHP: " . $cuentas . "');</script>");
        return view('cuentas.index', compact('cuentas'));
    }

    public function create()
    {
        $cuentasPadre = CuentasContables::where('es_movimiento', false)
            ->where('nivel', '<', 5)
            ->get();
        return view('cuentas.create', compact('cuentasPadre'));
    }


    private function determinarNivelPorCodigo($codigoCuenta)
    {
        // Extraer los valores clave del código de cuenta
        $segundoNivel = substr($codigoCuenta, 1, 1); // Segundo dígito
        $cuartoNivel = substr($codigoCuenta, 3, 1);  // Cuarto dígito
        $sextoNivel = substr($codigoCuenta, 5, 1);   // Sexto dígito
        $octavoNivel = substr($codigoCuenta, 7, 1);  // Octavo dígito
        $ultimoDigitos = substr($codigoCuenta, 8, 2); // Últimos 2 dígitos

        // Determinar el nivel basándonos en la jerarquía del código
        if ($segundoNivel == '0' && $cuartoNivel == '0' && $sextoNivel == '0' && $octavoNivel == '0') {
            return 1; // Nivel 1
        } elseif ($cuartoNivel == '0' && $sextoNivel == '0' && $octavoNivel == '0') {
            return 2; // Nivel 2
        } elseif ($sextoNivel == '0' && $octavoNivel == '0') {
            return 3; // Nivel 3
        } elseif ($octavoNivel == '0' && $ultimoDigitos == '00') {
            return 4; // Nivel 4
        } else {
            return 5; // Nivel 5
        }
    }

    // Guardar una nueva cuenta
    public function store(Request $request)
    {
        $request->validate([
            'nombre_cuenta' => 'required|string|max:255',
            'tipo_cuenta' => 'required|in:Activo,Pasivo,Patrimonio,Ingresos,Egresos',
            'parent_id' => 'nullable|exists:cuentas,id_cuenta',
            'es_movimiento' => 'sometimes|boolean',
        ]);

        // ✅ Validación: Solo una cuenta raíz por tipo
        if (empty($request->parent_id)) {
            $existe = CuentasContables::where('tipo_cuenta', $request->tipo_cuenta)
                ->whereNull('parent_id')
                ->exists();

            if ($existe) {
                return back()
                    ->withErrors(['tipo_cuenta' => 'Ya existe una cuenta raíz para este tipo.'])
                    ->withInput();
            }
        }

        if (!empty($request->parent_id)) {
            $parent = CuentasContables::find($request->parent_id);

            if ($parent && $parent->tipo_cuenta !== $request->tipo_cuenta) {
                return back()
                    ->withErrors(['parent_id' => 'La cuenta padre debe ser del mismo tipo que la cuenta.'])
                    ->withInput();
            }
        }

        // Inicializar datos
        $data = $request->only(['nombre_cuenta', 'tipo_cuenta', 'parent_id', 'es_movimiento']);

        // Si tiene padre y este es de movimiento, lo desactivamos
        if ($request->filled('parent_id')) {
            $parent = CuentasContables::find($request->parent_id);
            if ($parent && $parent->es_movimiento) {
                $parent->es_movimiento = false;
                $parent->save();
            }
        }

        try {
            CuentasContables::create($data);
            return redirect()->route('show.cuentas.home')->with('success', 'Cuenta creada correctamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al crear la cuenta: ' . $e->getMessage()])->withInput();
        }
    }

    // Mostrar formulario de edición
    public function edit($id)
    {
        // Buscar la cuenta en la base de datos por su ID
        $cuenta = CuentasContables::find($id);

        // Validar si no se encuentra la cuenta
        if (!$cuenta) {
            return response()->json(['error' => 'Cuenta no encontrada'], 404);
        }

        // Asegurar que el campo 'nivel' sea siempre un array
        $nivelesSeleccionados = is_array($cuenta->nivel) ? $cuenta->nivel : explode(',', $cuenta->nivel);

        // Devolver los datos de la cuenta en formato JSON
        return response()->json([
            'id_cuenta'      => $cuenta->id_cuenta,
            'nombre_cuenta'  => $cuenta->nombre_cuenta,
            'codigo_cuenta'  => $cuenta->codigo_cuenta,
            'tipo_cuenta'    => $cuenta->tipo_cuenta,
            'nivel'          => $nivelesSeleccionados, // Array de niveles seleccionados
            'es_movimiento'  => (int) $cuenta->es_movimiento, // Aseguramos que sea 0 o 1
        ]);
    }

    // Actualizar los datos de la cuenta
    public function update(Request $request, $id)
    {
        // Validar los datos del formulario
        $request->validate([
            'nombre_cuenta' => 'required|string|max:255',
            'tipo_cuenta' => 'required|string',
            'es_movimiento' => 'sometimes|boolean'
        ]);

        // Buscar la cuenta por ID
        $cuenta = CuentasContables::find($id);

        // Validar si no se encuentra la cuenta
        if (!$cuenta) {
            return redirect()->route('cuentas.index')->with('error', 'Cuenta no encontrada');
        }

        // Revisar si tiene cuentas hijas
        $hasChildren = CuentasContables::where('parent_id', $cuenta->id_cuenta)->exists();
        
        // Validación: si tiene padre, no se puede cambiar tipo_cuenta
        if ($cuenta->parent_id) {
            // Account has a parent → block changes to tipo_cuenta
            if ($cuenta->tipo_cuenta !== $request->tipo_cuenta) {
                return back()
                    ->withErrors(['tipo_cuenta' => 'No se puede cambiar el tipo de cuenta si la cuenta tiene un padre.'])
                    ->withInput();
            }
        } else {
            // Root account → allow changing tipo_cuenta
            if ($cuenta->tipo_cuenta !== $request->tipo_cuenta) {
                $cuenta->tipo_cuenta = $request->input('tipo_cuenta');
                $cuenta->codigo_cuenta = CuentasContables::generarCodigoCuenta($cuenta);
            }
        }

        // Actualizar los campos de la cuenta
        $cuenta->nombre_cuenta = $request->input('nombre_cuenta');

        // Validar que no pueda ser marcada como movimiento si tiene hijas
        if ($request->boolean('es_movimiento') && $hasChildren) {
            return back()
                ->withErrors(['es_movimiento' => 'No se puede marcar como cuenta de movimiento porque tiene cuentas hijas.'])
                ->withInput();
        }
        
        // es_movimiento depende del nivel y de si tiene hijas
        if ($hasChildren) {
            $cuenta->es_movimiento = false;
        } else {
            $cuenta->es_movimiento = match (true) {
                $cuenta->nivel === 5 => true,
                $cuenta->nivel === 4 => $request->boolean('es_movimiento', false),
                default => false
            };
        }

        try {
            // Guardar los cambios en la base de datos
            $cuenta->save();
            return redirect()->route('cuentas.index')->with('success', 'Cuenta actualizada exitosamente.');
        } catch (\Exception $e) {
            // Manejar errores durante la actualización
            return redirect()->route('cuentas.index')->with('error', 'Error al actualizar la cuenta: ' . $e->getMessage());
        }
    }

    // Eliminar una cuenta
    public function destroy($id)
    {
        $cuenta = CuentasContables::find($id);

        if (!$cuenta) {
            return response()->json(['success' => false, 'message' => 'Cuenta no encontrada.'], 404);
        }

        $cuenta->delete();

        return response()->json(['success' => true, 'message' => 'Cuenta eliminada correctamente.']);
    }
}
