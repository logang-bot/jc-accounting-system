<?php

namespace App\Http\Controllers;

use App\Models\CuentasContables;
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
        ]);

        $empresa = Empresa::create([
            'name' => $request->name,
            'nit' => $request->nit,
        ]);

        // Crear cuentas raíz para la empresa
        // $tipos = ['Activo', 'Pasivo', 'Patrimonio', 'Ingresos', 'Egresos'];

        // foreach ($tipos as $tipo) {
        //     $existe = CuentasContables::where('empresa_id', $empresa->id)
        //         ->whereNull('parent_id')
        //         ->where('tipo_cuenta', $tipo)
        //         ->exists();

        //     if (!$existe) {
        //         CuentasContables::create([
        //             'nombre_cuenta' => $tipo,
        //             'tipo_cuenta' => $tipo,
        //             'codigo_cuenta' => self::generarCodigoRaiz($tipo),
        //             'nivel' => 1,
        //             'es_movimiento' => false,
        //             'empresa_id' => $empresa->id,
        //         ]);
        //     }
        // }

        // Crear cuentas para Activo
        $this->crearCuentasActivos($empresa);
        $this->crearCuentasPasivos($empresa);
        $this->crearCuentasPatrimonio($empresa);
        $this->crearCuentasIngresos($empresa);
        $this->crearCuentasEgresos($empresa);

        return redirect()->route('show.empresas.create')->with('success', 'Empresa creada correctamente.');
    }

    public static function crearCuentasActivos(Empresa $empresa) 
    {
        $activo = CuentasContables::create([
            'nombre_cuenta' => 'ACTIVO',
            'tipo_cuenta' => 'Activo',
            'codigo_cuenta' => self::generarCodigoRaiz('Activo'),
            'nivel' => 1,
            'es_movimiento' => false,
            'empresa_id' => $empresa->id,
        ]);

        // -------------------- ACTIVO CORRIENTE --------------------
        $activoCorriente = CuentasContables::create([
            'nombre_cuenta' => 'ACTIVO CORRIENTE',
            'tipo_cuenta' => 'Activo',
            'nivel' => 2,
            'es_movimiento' => false,
            'parent_id' => $activo->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $disponibilidades = CuentasContables::create([
            'nombre_cuenta' => 'DISPONIBILIDADES',
            'tipo_cuenta' => 'Activo',
            'nivel' => 3,
            'es_movimiento' => false,
            'parent_id' => $activoCorriente->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'CAJA MONEDA NACIONAL',
            'tipo_cuenta' => 'Activo',
            'nivel' => 4,
            'es_movimiento' => true,
            'parent_id' => $disponibilidades->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'CAJA MONEDA EXTRANJERA',
            'tipo_cuenta' => 'Activo',
            'nivel' => 4,
            'es_movimiento' => true,
            'parent_id' => $disponibilidades->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $activoExigible = CuentasContables::create([
            'nombre_cuenta' => 'ACTIVO EXIGIBLE',
            'tipo_cuenta' => 'Activo',
            'nivel' => 3,
            'es_movimiento' => false,
            'parent_id' => $activoCorriente->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'CUENTAS POR COBRAR',
            'tipo_cuenta' => 'Activo',
            'nivel' => 4,
            'es_movimiento' => true,
            'parent_id' => $activoExigible->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'DOCUMENTOS POR COBRAR',
            'tipo_cuenta' => 'Activo',
            'nivel' => 4,
            'es_movimiento' => true,
            'parent_id' => $activoExigible->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $activoRealizable = CuentasContables::create([
            'nombre_cuenta' => 'ACTIVO REALIZABLE',
            'tipo_cuenta' => 'Activo',
            'nivel' => 3,
            'es_movimiento' => false,
            'parent_id' => $activoCorriente->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'INVENTARIOS',
            'tipo_cuenta' => 'Activo',
            'nivel' => 4,
            'es_movimiento' => false,
            'parent_id' => $activoRealizable->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'MERCADERÍAS',
            'tipo_cuenta' => 'Activo',
            'nivel' => 4,
            'es_movimiento' => true,
            'parent_id' => $activoRealizable->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'PRODUCTOS TERMINADOS',
            'tipo_cuenta' => 'Activo',
            'nivel' => 4,
            'es_movimiento' => true,
            'parent_id' => $activoRealizable->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'PRODUCTOS EN PROCESO',
            'tipo_cuenta' => 'Activo',
            'nivel' => 4,
            'es_movimiento' => true,
            'parent_id' => $activoRealizable->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);
        
        CuentasContables::create([
            'nombre_cuenta' => 'BANCOS MONEDA NACIONAL',
            'tipo_cuenta' => 'Activo',
            'nivel' => 4,
            'es_movimiento' => true,
            'parent_id' => $activoRealizable->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'BANCOS MONEDA EXTRANJERA',
            'tipo_cuenta' => 'Activo',
            'nivel' => 4,
            'es_movimiento' => true,
            'parent_id' => $activoRealizable->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        // -------------------- ACTIVO NO CORRIENTE --------------------

        $activoNoCorriente = CuentasContables::create([
            'nombre_cuenta' => 'ACTIVO NO CORRIENTE',
            'tipo_cuenta' => 'Activo',
            'nivel' => 2,
            'es_movimiento' => false,
            'parent_id' => $activo->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $inversiones = CuentasContables::create([
            'nombre_cuenta' => 'INVERSIONES',
            'tipo_cuenta' => 'Activo',
            'nivel' => 3,
            'es_movimiento' => false,
            'parent_id' => $activoNoCorriente->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'INVERSIONES - ACCIONES',
            'tipo_cuenta' => 'Activo',
            'nivel' => 4,
            'es_movimiento' => true,
            'parent_id' => $inversiones->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'INVERSIONES - BONOS',
            'tipo_cuenta' => 'Activo',
            'nivel' => 4,
            'es_movimiento' => true,
            'parent_id' => $inversiones->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $activoFijo = CuentasContables::create([
            'nombre_cuenta' => 'ACTIVO FIJO',
            'tipo_cuenta' => 'Activo',
            'nivel' => 3,
            'es_movimiento' => false,
            'parent_id' => $activoNoCorriente->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $activosFijos = [
            'TERRENOS',
            'EDIFICIOS Y CONSTRUCCIONES',
            'MAQUINARIAS Y EQUIPOS',
            'HERRAMIENTAS',
            'MUEBLES Y ENSERES',
            'EQUIPOS DE COMPUTACIÓN',
            'INSTALACIONES',
            'VEHÍCULOS'
        ];

        foreach ($activosFijos as $nombre) {
            CuentasContables::create([
                'nombre_cuenta' => $nombre,
                'tipo_cuenta' => 'Activo',
                'nivel' => 4,
                'es_movimiento' => true,
                'parent_id' => $activoFijo->id_cuenta,
                'empresa_id' => $empresa->id,
            ]);
        }

        $depreciacion = CuentasContables::create([
            'nombre_cuenta' => 'DEPRECIACIÓN ACUMULADA ACTIVOS FIJOS',
            'tipo_cuenta' => 'Activo',
            'nivel' => 3,
            'es_movimiento' => false,
            'parent_id' => $activoNoCorriente->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $depAcumuladas = [
            'DEP. ACUM. EDIFICIOS Y CONSTRUCCIONES',
            'DEP. ACUM. MAQUINARIAS Y EQUIPOS',
            'DEP. ACUM. HERRAMIENTAS',
            'DEP. ACUM. MUEBLES Y ENSERES',
            'DEP. ACUM. EQUIPOS DE COMPUTACIÓN',
            'DEP. ACUM. INSTALACIONES',
            'DEP. ACUM. VEHÍCULOS',
        ];

        foreach ($depAcumuladas as $nombre) {
            CuentasContables::create([
                'nombre_cuenta' => $nombre,
                'tipo_cuenta' => 'Activo',
                'nivel' => 4,
                'es_movimiento' => true,
                'parent_id' => $depreciacion->id_cuenta,
                'empresa_id' => $empresa->id,
            ]);
        }

        $activoDiferido = CuentasContables::create([
            'nombre_cuenta' => 'ACTIVO DIFERIDO',
            'tipo_cuenta' => 'Activo',
            'nivel' => 3,
            'es_movimiento' => false,
            'parent_id' => $activoNoCorriente->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'GASTOS PAGADOS POR ANTICIPADO',
            'tipo_cuenta' => 'Activo',
            'nivel' => 4,
            'es_movimiento' => true,
            'parent_id' => $activoDiferido->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

    }

    private function crearCuentasPasivos(Empresa $empresa)
    {
        $pasivo = CuentasContables::create([
            'nombre_cuenta' => 'PASIVO',
            'tipo_cuenta' => 'Pasivo',
            'codigo_cuenta' => self::generarCodigoRaiz('Pasivo'),
            'nivel' => 1,
            'es_movimiento' => false,
            'empresa_id' => $empresa->id,
        ]);

        // -------------------- PASIVO CORRIENTE --------------------
        $pasivoCorriente = CuentasContables::create([
            'nombre_cuenta' => 'PASIVO CORRIENTE',
            'tipo_cuenta' => 'Pasivo',
            'nivel' => 2,
            'es_movimiento' => false,
            'parent_id' => $pasivo->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $pasivoExigible = CuentasContables::create([
            'nombre_cuenta' => 'PASIVO EXIGIBLE',
            'tipo_cuenta' => 'Pasivo',
            'nivel' => 3,
            'es_movimiento' => false,
            'parent_id' => $pasivoCorriente->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $cuentasExigibles = [
            'CUENTAS POR PAGAR M/N',
            'CUENTAS POR PAGAR M/E',
            'DOCUMENTOS POR COBRAR M/N',
            'DOCUMENTOS POR COBRAR M/E',
            'CUENTAS POR PAGAR AL PERSONAL M/N',
            'HONORARIOS PROFESIONALES POR PAGAR',
            'SUELDOS POR PAGAR',
            'AGUINALDOS POR PAGAR',
            'BENEFICIOS SOCIALES POR PAGAR',
            'PROVEEDORES LOCALES POR PAGAR M/N',
            'PROVEEDORES LOCALES POR PAGAR M/E',
            'PROVEEDORES INTERIOR POR PAGAR M/N',
            'PROVEEDORES INTERIOR POR PAGAR M/E',
            'PROVEEDORES EXTERIOR POR PAGAR M/E',
            'I.U.E. POR PAGAR',
            'I.V.A. - DÉBITO FISCAL',
            'I.T. - TRANSACCIONES POR PAGAR',
            'R.C. - I.V.A. RÉGIMEN COMPLEMENTARIO IVA',
            'I.C.E. - CONSUMO ESPECÍFICO FIJO POR PAGAR',
            'I.C.E. - CONSUMO ESPECÍFICO VARIABLE POR PAGAR',
            'IMPUESTOS Y RETENCIONES POR PAGAR',
            'LIQUIDACIÓN DE IMPUESTOS',
            'PATENTES MUNICIPALES',
        ];

        foreach ($cuentasExigibles as $nombreCuenta) {
            CuentasContables::create([
                'nombre_cuenta' => $nombreCuenta,
                'tipo_cuenta' => 'Pasivo',
                'nivel' => 4,
                'es_movimiento' => true,
                'parent_id' => $pasivoExigible->id_cuenta,
                'empresa_id' => $empresa->id,
            ]);
        }

        // -------------------- PASIVO NO CORRIENTE --------------------
        $pasivoNoCorriente = CuentasContables::create([
            'nombre_cuenta' => 'PASIVO NO CORRIENTE',
            'tipo_cuenta' => 'Pasivo',
            'nivel' => 2,
            'es_movimiento' => false,
            'parent_id' => $pasivo->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $deudasLargoPlazo = CuentasContables::create([
            'nombre_cuenta' => 'DEUDAS A LARGO PLAZO',
            'tipo_cuenta' => 'Pasivo',
            'nivel' => 3,
            'es_movimiento' => false,
            'parent_id' => $pasivoNoCorriente->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'PRÉSTAMOS BANCARIOS',
            'tipo_cuenta' => 'Pasivo',
            'nivel' => 4,
            'es_movimiento' => true,
            'parent_id' => $deudasLargoPlazo->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);
    }

    private function crearCuentasPatrimonio(Empresa $empresa)
    {
        $patrimonio = CuentasContables::create([
            'nombre_cuenta' => 'PATRIMONIO',
            'tipo_cuenta' => 'Patrimonio',
            'codigo_cuenta' => self::generarCodigoRaiz('Patrimonio'),
            'nivel' => 1,
            'es_movimiento' => false,
            'empresa_id' => $empresa->id,
        ]);

        // -------------------- CAPITAL SOCIAL --------------------
        $capitalSocial = CuentasContables::create([
            'nombre_cuenta' => 'CAPITAL SOCIAL',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 2,
            'es_movimiento' => false,
            'parent_id' => $patrimonio->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $capitalPagado = CuentasContables::create([
            'nombre_cuenta' => 'CAPITAL PAGADO',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 3,
            'es_movimiento' => false,
            'parent_id' => $capitalSocial->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'CAPITAL SUSCRITO Y PAGADO',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 4,
            'es_movimiento' => true,
            'parent_id' => $capitalPagado->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'CAPITAL NO PAGADO',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 4,
            'es_movimiento' => true,
            'parent_id' => $capitalPagado->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $reservas = CuentasContables::create([
            'nombre_cuenta' => 'RESERVAS',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 3,
            'es_movimiento' => false,
            'parent_id' => $capitalSocial->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $reservasHijas = [
            'RESERVA LEGAL',
            'RESERVA ESTATUTARIA',
            'RESERVA OCASIONAL',
            'RESERVA POR REVALUACIÓN',
        ];

        foreach ($reservasHijas as $nombre) {
            CuentasContables::create([
                'nombre_cuenta' => $nombre,
                'tipo_cuenta' => 'Patrimonio',
                'nivel' => 4,
                'es_movimiento' => true,
                'parent_id' => $reservas->id_cuenta,
                'empresa_id' => $empresa->id,
            ]);
        }

        $resultados = CuentasContables::create([
            'nombre_cuenta' => 'RESULTADOS ACUMULADOS',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 3,
            'es_movimiento' => false,
            'parent_id' => $capitalSocial->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'RESULTADOS DE EJERCICIOS ANTERIORES',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 4,
            'es_movimiento' => true,
            'parent_id' => $resultados->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        // -------------------- PATRIMONIO APORTADO --------------------
        $patrimonioAportado = CuentasContables::create([
            'nombre_cuenta' => 'PATRIMONIO APORTADO',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 2,
            'es_movimiento' => false,
            'parent_id' => $patrimonio->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $capitalSocial2 = CuentasContables::create([
            'nombre_cuenta' => 'CAPITAL SOCIAL',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 3,
            'es_movimiento' => false,
            'parent_id' => $patrimonioAportado->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'CAPITAL SUSCRITO',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 4,
            'es_movimiento' => true,
            'parent_id' => $capitalSocial2->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'CAPITAL INTEGRADO',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 4,
            'es_movimiento' => true,
            'parent_id' => $capitalSocial2->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $primasEmision = CuentasContables::create([
            'nombre_cuenta' => 'PRIMAS DE EMISIÓN',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 3,
            'es_movimiento' => false,
            'parent_id' => $patrimonioAportado->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'PRIMAS DE SUSCRIPCIÓN',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 4,
            'es_movimiento' => true,
            'parent_id' => $primasEmision->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $excedentes = CuentasContables::create([
            'nombre_cuenta' => 'EXCEDENTES ACUMULADOS',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 3,
            'es_movimiento' => false,
            'parent_id' => $patrimonioAportado->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'EXCEDENTES DEL EJERCICIO',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 4,
            'es_movimiento' => true,
            'parent_id' => $excedentes->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'EXCEDENTES NO DISTRIBUIDOS',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 4,
            'es_movimiento' => true,
            'parent_id' => $excedentes->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $aportes = CuentasContables::create([
            'nombre_cuenta' => 'APORTES DE SOCIOS',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 3,
            'es_movimiento' => false,
            'parent_id' => $patrimonioAportado->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'APORTES DE CAPITAL',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 4,
            'es_movimiento' => true,
            'parent_id' => $aportes->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'APORTES ADICIONALES',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 4,
            'es_movimiento' => true,
            'parent_id' => $aportes->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);
    }

    private function crearCuentasIngresos(Empresa $empresa)
    {
        $ingresos = CuentasContables::create([
            'nombre_cuenta' => 'INGRESOS',
            'tipo_cuenta' => 'Ingresos',
            'codigo_cuenta' => self::generarCodigoRaiz('Ingresos'),
            'nivel' => 1,
            'es_movimiento' => false,
            'empresa_id' => $empresa->id,
        ]);

        // -------------------- INGRESOS OPERATIVOS --------------------
        $ingresosOperativos = CuentasContables::create([
            'nombre_cuenta' => 'INGRESOS OPERATIVOS',
            'tipo_cuenta' => 'Ingresos',
            'nivel' => 2,
            'es_movimiento' => false,
            'parent_id' => $ingresos->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $ventas = CuentasContables::create([
            'nombre_cuenta' => 'VENTAS',
            'tipo_cuenta' => 'Ingresos',
            'nivel' => 3,
            'es_movimiento' => false,
            'parent_id' => $ingresosOperativos->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $ventasHijas = [
            'VENTAS PRODUCTOS TERMINADOS',
            'VENTAS MERCADERÍAS',
            'VENTAS SERVICIOS',
            'DEVOLUCIONES SOBRE VENTAS',
        ];

        foreach ($ventasHijas as $nombre) {
            CuentasContables::create([
                'nombre_cuenta' => $nombre,
                'tipo_cuenta' => 'Ingresos',
                'nivel' => 4,
                'es_movimiento' => true,
                'parent_id' => $ventas->id_cuenta,
                'empresa_id' => $empresa->id,
            ]);
        }

        // -------------------- INGRESOS NO OPERATIVOS --------------------
        $ingresosNoOperativos = CuentasContables::create([
            'nombre_cuenta' => 'INGRESOS NO OPERATIVOS',
            'tipo_cuenta' => 'Ingresos',
            'nivel' => 2,
            'es_movimiento' => false,
            'parent_id' => $ingresos->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $ingresosFinancieros = CuentasContables::create([
            'nombre_cuenta' => 'INGRESOS FINANCIEROS',
            'tipo_cuenta' => 'Ingresos',
            'nivel' => 3,
            'es_movimiento' => false,
            'parent_id' => $ingresosNoOperativos->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $ingresosFinancierosHijos = [
            'INTERESES BANCARIOS',
            'RENTAS SOBRE INVERSIONES',
        ];

        foreach ($ingresosFinancierosHijos as $nombre) {
            CuentasContables::create([
                'nombre_cuenta' => $nombre,
                'tipo_cuenta' => 'Ingresos',
                'nivel' => 4,
                'es_movimiento' => true,
                'parent_id' => $ingresosFinancieros->id_cuenta,
                'empresa_id' => $empresa->id,
            ]);
        }

        $ingresosExtraordinarios = CuentasContables::create([
            'nombre_cuenta' => 'INGRESOS EXTRAORDINARIOS',
            'tipo_cuenta' => 'Ingresos',
            'nivel' => 3,
            'es_movimiento' => false,
            'parent_id' => $ingresosNoOperativos->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $ingresosExtraordinariosHijos = [
            'VENTA DE ACTIVOS FIJOS',
            'RENTAS EXTRAORDINARIAS',
        ];

        foreach ($ingresosExtraordinariosHijos as $nombre) {
            CuentasContables::create([
                'nombre_cuenta' => $nombre,
                'tipo_cuenta' => 'Ingresos',
                'nivel' => 4,
                'es_movimiento' => true,
                'parent_id' => $ingresosExtraordinarios->id_cuenta,
                'empresa_id' => $empresa->id,
            ]);
        }

        // -------------------- OTROS INGRESOS --------------------
        $otrosIngresos = CuentasContables::create([
            'nombre_cuenta' => 'OTROS INGRESOS',
            'tipo_cuenta' => 'Ingresos',
            'nivel' => 2,
            'es_movimiento' => false,
            'parent_id' => $ingresos->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $donaciones = CuentasContables::create([
            'nombre_cuenta' => 'DONACIONES',
            'tipo_cuenta' => 'Ingresos',
            'nivel' => 3,
            'es_movimiento' => false,
            'parent_id' => $otrosIngresos->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $donacionesHijas = [
            'DONACIONES EN EFECTIVO',
            'DONACIONES EN ESPECIE',
        ];

        foreach ($donacionesHijas as $nombre) {
            CuentasContables::create([
                'nombre_cuenta' => $nombre,
                'tipo_cuenta' => 'Ingresos',
                'nivel' => 4,
                'es_movimiento' => true,
                'parent_id' => $donaciones->id_cuenta,
                'empresa_id' => $empresa->id,
            ]);
        }

        $ingresosServicios = CuentasContables::create([
            'nombre_cuenta' => 'INGRESOS POR SERVICIOS',
            'tipo_cuenta' => 'Ingresos',
            'nivel' => 3,
            'es_movimiento' => false,
            'parent_id' => $otrosIngresos->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $serviciosHijos = [
            'HONORARIOS POR SERVICIOS',
            'COMISIONES POR SERVICIOS',
        ];

        foreach ($serviciosHijos as $nombre) {
            CuentasContables::create([
                'nombre_cuenta' => $nombre,
                'tipo_cuenta' => 'Ingresos',
                'nivel' => 4,
                'es_movimiento' => true,
                'parent_id' => $ingresosServicios->id_cuenta,
                'empresa_id' => $empresa->id,
            ]);
        }
    }

    private function crearCuentasEgresos(Empresa $empresa)
    {
        $egresos = CuentasContables::create([
            'nombre_cuenta' => 'EGRESOS',
            'tipo_cuenta' => 'Egresos',
            'codigo_cuenta' => self::generarCodigoRaiz('Egresos'),
            'nivel' => 1,
            'es_movimiento' => false,
            'empresa_id' => $empresa->id,
        ]);

        // -------------------- COSTO DE VENTAS --------------------
        $costoVentas = CuentasContables::create([
            'nombre_cuenta' => 'COSTO DE VENTAS',
            'tipo_cuenta' => 'Egresos',
            'nivel' => 2,
            'es_movimiento' => false,
            'parent_id' => $egresos->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $costoProductosVendidos = CuentasContables::create([
            'nombre_cuenta' => 'COSTO DE PRODUCTOS VENDIDOS',
            'tipo_cuenta' => 'Egresos',
            'nivel' => 3,
            'es_movimiento' => false,
            'parent_id' => $costoVentas->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $cuentasCostoProductos = [
            'MATERIALES DIRECTOS',
            'MANO DE OBRA DIRECTA',
            'COSTOS INDIRECTOS DE FABRICACIÓN',
        ];

        foreach ($cuentasCostoProductos as $nombre) {
            CuentasContables::create([
                'nombre_cuenta' => $nombre,
                'tipo_cuenta' => 'Egresos',
                'nivel' => 4,
                'es_movimiento' => true,
                'parent_id' => $costoProductosVendidos->id_cuenta,
                'empresa_id' => $empresa->id,
            ]);
        }

        // -------------------- GASTOS OPERATIVOS --------------------
        $gastosOperativos = CuentasContables::create([
            'nombre_cuenta' => 'GASTOS OPERATIVOS',
            'tipo_cuenta' => 'Egresos',
            'nivel' => 2,
            'es_movimiento' => false,
            'parent_id' => $egresos->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $gastosAdministracion = CuentasContables::create([
            'nombre_cuenta' => 'GASTOS DE ADMINISTRACIÓN',
            'tipo_cuenta' => 'Egresos',
            'nivel' => 3,
            'es_movimiento' => false,
            'parent_id' => $gastosOperativos->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $cuentasAdministracion = [
            'SUELDOS Y SALARIOS',
            'ARRENDAMIENTOS',
            'GASTOS DE OFICINA',
        ];

        foreach ($cuentasAdministracion as $nombre) {
            CuentasContables::create([
                'nombre_cuenta' => $nombre,
                'tipo_cuenta' => 'Egresos',
                'nivel' => 4,
                'es_movimiento' => true,
                'parent_id' => $gastosAdministracion->id_cuenta,
                'empresa_id' => $empresa->id,
            ]);
        }

        // -------------------- GASTOS DE VENTA --------------------
        $gastosVenta = CuentasContables::create([
            'nombre_cuenta' => 'GASTOS DE VENTA',
            'tipo_cuenta' => 'Egresos',
            'nivel' => 2,
            'es_movimiento' => false,
            'parent_id' => $egresos->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $publicidadPromocion = CuentasContables::create([
            'nombre_cuenta' => 'PUBLICIDAD Y PROMOCIÓN',
            'tipo_cuenta' => 'Egresos',
            'nivel' => 3,
            'es_movimiento' => false,
            'parent_id' => $gastosVenta->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $cuentasPublicidad = [
            'ANUNCIOS PUBLICITARIOS',
            'GASTOS DE PROMOCIÓN',
        ];

        foreach ($cuentasPublicidad as $nombre) {
            CuentasContables::create([
                'nombre_cuenta' => $nombre,
                'tipo_cuenta' => 'Egresos',
                'nivel' => 4,
                'es_movimiento' => true,
                'parent_id' => $publicidadPromocion->id_cuenta,
                'empresa_id' => $empresa->id,
            ]);
        }

        // -------------------- GASTOS FINANCIEROS --------------------
        $gastosFinancieros = CuentasContables::create([
            'nombre_cuenta' => 'GASTOS FINANCIEROS',
            'tipo_cuenta' => 'Egresos',
            'nivel' => 2,
            'es_movimiento' => false,
            'parent_id' => $egresos->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $interesesPagados = CuentasContables::create([
            'nombre_cuenta' => 'INTERESES PAGADOS',
            'tipo_cuenta' => 'Egresos',
            'nivel' => 3,
            'es_movimiento' => false,
            'parent_id' => $gastosFinancieros->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $cuentasIntereses = [
            'INTERESES BANCARIOS',
            'INTERESES DE DOCUMENTOS POR PAGAR',
        ];

        foreach ($cuentasIntereses as $nombre) {
            CuentasContables::create([
                'nombre_cuenta' => $nombre,
                'tipo_cuenta' => 'Egresos',
                'nivel' => 4,
                'es_movimiento' => true,
                'parent_id' => $interesesPagados->id_cuenta,
                'empresa_id' => $empresa->id,
            ]);
        }

        // -------------------- OTROS GASTOS --------------------
        $otrosGastos = CuentasContables::create([
            'nombre_cuenta' => 'OTROS GASTOS',
            'tipo_cuenta' => 'Egresos',
            'nivel' => 2,
            'es_movimiento' => false,
            'parent_id' => $egresos->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $perdidasExtraordinarias = CuentasContables::create([
            'nombre_cuenta' => 'PÉRDIDAS EXTRAORDINARIAS',
            'tipo_cuenta' => 'Egresos',
            'nivel' => 3,
            'es_movimiento' => false,
            'parent_id' => $otrosGastos->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $cuentasPerdidas = [
            'PÉRDIDAS POR VENTA DE ACTIVOS FIJOS',
            'PÉRDIDAS POR ROBO O DAÑOS',
        ];

        foreach ($cuentasPerdidas as $nombre) {
            CuentasContables::create([
                'nombre_cuenta' => $nombre,
                'tipo_cuenta' => 'Egresos',
                'nivel' => 4,
                'es_movimiento' => true,
                'parent_id' => $perdidasExtraordinarias->id_cuenta,
                'empresa_id' => $empresa->id,
            ]);
        }
    }

    public static function generarCodigoRaiz($tipo)
    {
        return match ($tipo) {
            'Activo' => '1000000000',
            'Pasivo' => '2000000000',
            'Patrimonio' => '3000000000',
            'Ingresos' => '4000000000',
            'Egresos' => '5000000000',
            default => '-1',
        };
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
