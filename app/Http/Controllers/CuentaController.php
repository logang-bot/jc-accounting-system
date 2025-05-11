<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CuentasContables;

class CuentaController extends Controller
{
    // Mostrar todas las cuentas
    public function index()
    {
        // Obtener todas las cuentas principales con sus subcuentas
        $cuentas = CuentasContables::whereNull('parent_id') // Solo las cuentas principales
            ->with(['children' => function ($query) {
                $query->orderBy('codigo_cuenta', 'asc'); // Ordenar subcuentas por código
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
        foreach ($cuentas as $cuenta) {
            $cuenta->nivel = $this->determinarNivelPorCodigo($cuenta->codigo_cuenta);

            // Asignar niveles a las subcuentas
            foreach ($cuenta->children as $subcuenta) {
                $subcuenta->nivel = $this->determinarNivelPorCodigo($subcuenta->codigo_cuenta);
            }
        }

        // Pasar las cuentas a la vista
        return view('cuentas.index', compact('cuentas'));
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
            'codigo_cuenta' => 'nullable|unique:cuentas,codigo_cuenta|max:10',
            'nombre_cuenta' => 'required|string|max:255',
            'tipo_cuenta' => 'required|in:Activo,Pasivo,Patrimonio,Ingresos,Egresos',
            'nivel' => 'nullable|string',
            'parent_id' => 'nullable|exists:cuentas,id_cuenta',
            'es_movimiento' => 'sometimes|boolean',
        ]);

        $niveles = [
            "Grupo" => 1,
            "Rubro" => 2,
            "Título" => 3,
            "Cta-Compuesta" => 4,
            "Sub-Cuenta" => 5
        ];

        // Inicializar datos
        $data = $request->only(['codigo_cuenta', 'nombre_cuenta', 'tipo_cuenta', 'parent_id']);

        // Determinar el nivel de la cuenta
        if (!empty($request->parent_id)) {
            $parent = CuentasContables::find($request->parent_id);
            $data['nivel'] = $parent ? intval($parent->nivel) + 1 : 1;
        } else {
            $data['nivel'] = $niveles[$request->nivel] ?? 1;
        }

        // Asegurar que nivel sea numérico
        $data['nivel'] = is_numeric($data['nivel']) ? intval($data['nivel']) : 1;

        // Generar código de cuenta si no se proporcionó
        if (empty($request->codigo_cuenta)) {
            $data['codigo_cuenta'] = $this->generarCodigoCuenta($data['tipo_cuenta'], $data['parent_id'] ?? null);
        }

        // Forzar es_movimiento en nivel 5
        if ($data['nivel'] === 5) {
            $data['es_movimiento'] = true;
        } elseif ($data['nivel'] === 4) {
            $data['es_movimiento'] = $request->has('es_movimiento') ? $request->boolean('es_movimiento') : false;
        } else {
            $data['es_movimiento'] = false;
        }

        // Crear la cuenta
        CuentasContables::create($data);

        return redirect()->route('cuentas.index')->with('success', 'Cuenta creada correctamente.');
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
            'codigo_cuenta' => 'required|string|max:10|unique:cuentas_contables,codigo_cuenta,' . $id,
            'tipo_cuenta' => 'required|string',
            'nivel' => 'required|array|min:1', // 'nivel' debe ser un array
            'estado' => 'required|boolean'
        ]);

        // Buscar la cuenta por ID
        $cuenta = CuentasContables::find($id);

        // Validar si no se encuentra la cuenta
        if (!$cuenta) {
            return redirect()->route('cuentas.index')->with('error', 'Cuenta no encontrada');
        }

        // Actualizar los campos de la cuenta
        $cuenta->nombre_cuenta = $request->input('nombre_cuenta');
        $cuenta->codigo_cuenta = $request->input('codigo_cuenta');
        $cuenta->tipo_cuenta = $request->input('tipo_cuenta');
        $cuenta->nivel = implode(",", $request->input('nivel')); // Convertir el array de niveles en una cadena separada por comas
        $cuenta->estado = $request->input('estado');

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
