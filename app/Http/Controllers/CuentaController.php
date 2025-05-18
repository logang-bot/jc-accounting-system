<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CuentasContables;
use Illuminate\Validation\ValidationException;

class CuentaController extends Controller
{
    // Mostrar todas las cuentas
    public function home()
    {
        // Obtener todas las cuentas principales con sus subcuentas
        $cuentas = CuentasContables::whereNull('parent_id') // Solo las cuentas principales
            ->where('estado', true)
            ->with('children')
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

        return view('cuentas.index', compact('cuentas'));
    }

    public function create()
    {
        $cuentasPadre = CuentasContables::where('es_movimiento', false)
            ->where('nivel', '<', 5)
            ->get();
            
        return view('cuentas.create', [
            'cuentasPadre' => $cuentasPadre,
            'modo' => 'crear',
            'cuenta' => null,
        ]);
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

        $this->validarCuentaRaiz($request);

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

    private function validarCuentaRaiz(Request $request)
    {
        $tiposPermitidos = ['Activo', 'Pasivo', 'Patrimonio', 'Ingresos', 'Egresos'];

        if ($request->filled('parent_id')) {
            return; // No es cuenta raíz, se permite cualquier tipo si el padre lo permite
        }

        if (!in_array($request->tipo_cuenta, $tiposPermitidos)) {
            throw ValidationException::withMessages([
                'tipo_cuenta' => 'Solo se permiten cuentas raíz con tipo: Activo, Pasivo, Patrimonio, Ingresos o Egresos.'
            ]);
        }

        $existe = CuentasContables::where('tipo_cuenta', $request->tipo_cuenta)
            ->whereNull('parent_id')
            ->exists();

        if ($existe) {
            throw ValidationException::withMessages([
                'tipo_cuenta' => 'Ya existe una cuenta raíz para este tipo.'
            ]);
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
        
        $cuentasPadre = CuentasContables::where('es_movimiento', false)
            ->where('nivel', '<', 5)
            ->where('id_cuenta', '!=', $id) // No puede ser su propio padre
            ->get();

        return view('cuentas.create', [
            'cuenta' => $cuenta,
            'cuentasPadre' => $cuentasPadre,
            'modo' => 'editar',
        ]);
    }

    // Actualizar los datos de la cuenta
    public function update(Request $request, $id)
    {
        // Validar los datos del formulario
        $request->validate([
            'nombre_cuenta' => 'required|string|max:255',
            'tipo_cuenta' => 'required|string',
            'parent_id' => 'nullable|exists:cuentas,id_cuenta',
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
        
        // Solo se permite editar tipo_cuenta y parent_id si NO tiene hijas
        $puedeCambiarEstructura = !$hasChildren;
        
        // Detectar si el tipo_cuenta o parent_id fueron modificados
        $tipoCuentaNuevo = $request->input('tipo_cuenta');
        $parentIdNuevo = $request->input('parent_id') ?: null;

        $cambioTipoCuenta = $cuenta->tipo_cuenta !== $tipoCuentaNuevo;
        $cambioParent = $cuenta->parent_id !== $parentIdNuevo;

        if (($cambioTipoCuenta || $cambioParent) && !$puedeCambiarEstructura) {
            return back()
                ->withErrors(['tipo_cuenta' => 'No se puede cambiar tipo ni ubicación de una cuenta que tiene cuentas hijas.'])
                ->withInput();
        }

        // Si se cambia tipo_cuenta o parent, actualizamos los datos jerárquicos
        if ($cambioTipoCuenta || $cambioParent) {
            $cuenta->tipo_cuenta = $tipoCuentaNuevo;
            $cuenta->parent_id = $parentIdNuevo;

            // Re-generar código de cuenta
            $cuenta->codigo_cuenta = CuentasContables::generarCodigoCuenta($cuenta);
            $cuenta->nivel = CuentasContables::calcularNivel($cuenta->codigo_cuenta);
        }

        // Actualizar los campos de la cuenta
        $cuenta->nombre_cuenta = $request->input('nombre_cuenta');

        // Validar si puede ser marcada como cuenta de movimiento
        if ($request->boolean('es_movimiento') && $hasChildren) {
            return back()
                ->withErrors(['es_movimiento' => 'No se puede marcar como cuenta de movimiento porque tiene cuentas hijas.'])
                ->withInput();
        }

        // Establecer es_movimiento correctamente
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
            return redirect()->route('show.cuentas.home')->with('success', 'Cuenta actualizada exitosamente.');
        } catch (\Exception $e) {
            // Manejar errores durante la actualización
            return redirect()->route('show.cuentas.home')->with('error', 'Error al actualizar la cuenta: ' . $e->getMessage());
        }
    }

    // Eliminar una cuenta
    public function destroy($id)
    {
        $cuenta = CuentasContables::find($id);

        if (!$cuenta) {
            return response()->json(['success' => false, 'message' => 'Cuenta no encontrada.'], 404);
        }

        // Evitar eliminar cuentas raíz
        if (is_null($cuenta->parent_id)) {
            return response()->json(['success' => false, 'message' => 'No se puede eliminar una cuenta raíz.'], 403);
        }

        // Verificar si tiene cuentas hijas
        if ($cuenta->children()->exists()) {
            return response()->json(['success' => false, 'message' => 'No se puede eliminar una cuenta que tiene subcuentas.'], 403);
        }

        // Recomendado en vez de eliminar de la base de datos
        $cuenta->estado = false;
        $cuenta->save();

        // Eliminacion real
        // $cuenta->delete();

        return response()->json(['success' => true, 'message' => 'Cuenta eliminada correctamente.']);
    }
}
