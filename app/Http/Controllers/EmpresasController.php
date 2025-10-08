<?php

namespace App\Http\Controllers;

use App\Models\CuentasContables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Empresa;
use App\Models\User;
use Spatie\Permission\Models\Role;

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
        $roles = Role::pluck('name');
        $empresas = Empresa::all();

        $users = Auth::user()->hasRole('Administrator')
            ? User::with('roles')->paginate(15)
            : collect();


        return view('empresas.create', [
            'roles' => $roles,
            'empresas' => $empresas,
            'users' => $users,
        ]);
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

        $caja = CuentasContables::create([
            'nombre_cuenta' => 'CAJA',
            'tipo_cuenta' => 'Activo',
            'nivel' => 4,
            'es_movimiento' => true,
            'parent_id' => $disponibilidades->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'CAJA MONEDA NACIONAL',
            'tipo_cuenta' => 'Activo',
            'nivel' => 5,
            'moneda_principal' => "BOB",
            'es_movimiento' => true,
            'parent_id' => $caja->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'CAJA MONEDA EXTRANJERA',
            'tipo_cuenta' => 'Activo',
            'nivel' => 5,
            'moneda_principal' => "USD",
            'es_movimiento' => true,
            'parent_id' => $caja->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $bancos = CuentasContables::create([
            'nombre_cuenta' => 'BANCOS',
            'tipo_cuenta' => 'Activo',
            'nivel' => 4,
            'es_movimiento' => true,
            'parent_id' => $disponibilidades->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'BANCO MONEDA NACIONAL',
            'tipo_cuenta' => 'Activo',
            'nivel' => 5,
            'moneda_principal' => "BOB",
            'es_movimiento' => true,
            'parent_id' => $bancos->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'BANCO MONEDA EXTRANJERA',
            'tipo_cuenta' => 'Activo',
            'nivel' => 5,
            'moneda_principal' => "USD",
            'es_movimiento' => true,
            'parent_id' => $bancos->id_cuenta,
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

        $cuentasPorCobrar = CuentasContables::create([
            'nombre_cuenta' => 'CUENTAS POR COBRAR',
            'tipo_cuenta' => 'Activo',
            'nivel' => 4,
            'es_movimiento' => true,
            'parent_id' => $activoExigible->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'CUENTAS POR COBRAR M/N',
            'tipo_cuenta' => 'Activo',
            'nivel' => 5,
            'moneda_principal' => "BOB",
            'es_movimiento' => true,
            'parent_id' => $cuentasPorCobrar->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'CUENTAS POR COBRAR M/E',
            'tipo_cuenta' => 'Activo',
            'nivel' => 5,
            'moneda_principal' => "USD",
            'es_movimiento' => true,
            'parent_id' => $cuentasPorCobrar->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $documentosPorCobrar = CuentasContables::create([
            'nombre_cuenta' => 'DOCUMENTOS POR COBRAR',
            'tipo_cuenta' => 'Activo',
            'nivel' => 4,
            'es_movimiento' => true,
            'parent_id' => $activoExigible->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'DOCUMENTOS POR COBRAR M/N',
            'tipo_cuenta' => 'Activo',
            'nivel' => 5,
            'moneda_principal' => "BOB",
            'es_movimiento' => true,
            'parent_id' => $documentosPorCobrar->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'DOCUMENTOS POR COBRAR M/E',
            'tipo_cuenta' => 'Activo',
            'nivel' => 5,
            'moneda_principal' => "USD",
            'es_movimiento' => true,
            'parent_id' => $documentosPorCobrar->id_cuenta,
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

        $inventarios = CuentasContables::create([
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
            'nivel' => 5,
            'moneda_principal' => "BOB",
            'es_movimiento' => true,
            'parent_id' => $inventarios->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'PRODUCTOS TERMINADOS',
            'tipo_cuenta' => 'Activo',
            'nivel' => 5,
            'moneda_principal' => "BOB",
            'es_movimiento' => true,
            'parent_id' => $inventarios->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'PRODUCTOS EN PROCESO',
            'tipo_cuenta' => 'Activo',
            'nivel' => 5,
            'moneda_principal' => "BOB",
            'es_movimiento' => true,
            'parent_id' => $inventarios->id_cuenta,
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
                'moneda_principal' => "BOB",
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
                'moneda_principal' => "BOB",
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
            'moneda_principal' => "BOB",
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

        $cuentasPorPagar = CuentasContables::create([
            'nombre_cuenta' => 'CUENTAS POR PAGAR',
            'tipo_cuenta' => 'Pasivo',
            'nivel' => 4,
            'es_movimiento' => false,
            'parent_id' => $pasivoExigible->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'CUENTAS POR PAGAR M/N',
            'tipo_cuenta' => 'Pasivo',
            'nivel' => 5,
            'moneda_principal' => "BOB",
            'es_movimiento' => false,
            'parent_id' => $cuentasPorPagar->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'CUENTAS POR PAGAR M/E',
            'tipo_cuenta' => 'Pasivo',
            'nivel' => 5,
            'moneda_principal' => "USD",
            'es_movimiento' => false,
            'parent_id' => $cuentasPorPagar->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $documentosPorPagar = CuentasContables::create([
            'nombre_cuenta' => 'DOCUMENTOS POR PAGAR',
            'tipo_cuenta' => 'Pasivo',
            'nivel' => 4,
            'es_movimiento' => false,
            'parent_id' => $pasivoExigible->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'DOCUMENTOS POR COBRAR M/N',
            'tipo_cuenta' => 'Pasivo',
            'nivel' => 5,
            'es_movimiento' => false,
            'moneda_principal' => "BOB",
            'parent_id' => $documentosPorPagar->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'DOCUMENTOS POR COBRAR M/E',
            'tipo_cuenta' => 'Pasivo',
            'nivel' => 5,
            'es_movimiento' => false,
            'moneda_principal' => "USD",
            'parent_id' => $documentosPorPagar->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $cuentasPorPagarAlPersonal = CuentasContables::create([
            'nombre_cuenta' => 'CUENTAS POR PAGAR AL PERSONAL',
            'tipo_cuenta' => 'Pasivo',
            'nivel' => 4,
            'es_movimiento' => false,
            'parent_id' => $pasivoExigible->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'CUENTAS POR PAGAR AL PERSONAL M/N',
            'tipo_cuenta' => 'Pasivo',
            'nivel' => 5,
            'es_movimiento' => false,
            'moneda_principal' => "BOB",
            'parent_id' => $cuentasPorPagarAlPersonal->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $obligacionesLaborales = CuentasContables::create([
            'nombre_cuenta' => 'OBLIGACIONES LABORALES',
            'tipo_cuenta' => 'Pasivo',
            'nivel' => 4,
            'es_movimiento' => false,
            'parent_id' => $pasivoExigible->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $cuentasObligacionesLaborales = [
            'HONORARIOS PROFESIONALES POR PAGAR',
            'SUELDOS POR PAGAR',
            'AGUINALDOS POR PAGAR',
            'BENEFICIOS SOCIALES POR PAGAR',
        ];

        foreach ($cuentasObligacionesLaborales as $nombreCuenta) {
            CuentasContables::create([
                'nombre_cuenta' => $nombreCuenta,
                'tipo_cuenta' => 'Pasivo',
                'nivel' => 5,
                'es_movimiento' => true,
                'moneda_principal' => "BOB",
                'parent_id' => $obligacionesLaborales->id_cuenta,
                'empresa_id' => $empresa->id,
            ]);
        }

        $proveedoresPorPagar = CuentasContables::create([
            'nombre_cuenta' => 'PROVEEDORES POR PAGAR',
            'tipo_cuenta' => 'Pasivo',
            'nivel' => 4,
            'es_movimiento' => false,
            'parent_id' => $pasivoExigible->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $cuentasproveedoresPorPagar = [
            'PROVEEDORES LOCALES POR PAGAR M/N',
            'PROVEEDORES LOCALES POR PAGAR M/E',
            'PROVEEDORES INTERIOR POR PAGAR M/N',
            'PROVEEDORES INTERIOR POR PAGAR M/E',
            'PROVEEDORES EXTERIOR POR PAGAR M/E',
        ];

        foreach ($cuentasproveedoresPorPagar as $nombreCuenta) {
            CuentasContables::create([
                'nombre_cuenta' => $nombreCuenta,
                'tipo_cuenta' => 'Pasivo',
                'nivel' => 5,
                'es_movimiento' => true,
                'moneda_principal' => "BOB",
                'parent_id' => $proveedoresPorPagar->id_cuenta,
                'empresa_id' => $empresa->id,
            ]);
        }

        $impuestosYRetencionesPorPagar = CuentasContables::create([
            'nombre_cuenta' => 'IMPUESTOS Y RETENCIONES POR PAGAR',
            'tipo_cuenta' => 'Pasivo',
            'nivel' => 3,
            'es_movimiento' => false,
            'parent_id' => $pasivoCorriente->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $impuestosDirectos = CuentasContables::create([
            'nombre_cuenta' => 'IMPUESTOS DIRECTOS',
            'tipo_cuenta' => 'Pasivo',
            'nivel' => 4,
            'es_movimiento' => false,
            'parent_id' => $impuestosYRetencionesPorPagar->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $cuentasimpuestosDirectos = [
            'I.U.E. POR PAGAR',
            'I.V.A. - DÉBITO FISCAL',
            'I.T. - TRANSACCIONES POR PAGAR',
            'R.C. - I.V.A. RÉGIMEN COMPLEMENTARIO IVA',
            'I.C.E. - CONSUMO ESPECÍFICO FIJO POR PAGAR',
            'I.C.E. - CONSUMO ESPECÍFICO VARIABLE POR PAGAR',
        ];

        foreach ($cuentasimpuestosDirectos as $nombreCuenta) {
            CuentasContables::create([
                'nombre_cuenta' => $nombreCuenta,
                'tipo_cuenta' => 'Pasivo',
                'nivel' => 5,
                'es_movimiento' => true,
                'moneda_principal' => "BOB",
                'parent_id' => $impuestosDirectos->id_cuenta,
                'empresa_id' => $empresa->id,
            ]);
        }

        $impuestosMunicipales = CuentasContables::create([
            'nombre_cuenta' => 'IMPUESTOS MUNICIPALES',
            'tipo_cuenta' => 'Pasivo',
            'nivel' => 4,
            'es_movimiento' => false,
            'parent_id' => $impuestosYRetencionesPorPagar->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'LIQUIDACIÓN DE IMPUESTOS',
            'tipo_cuenta' => 'Pasivo',
            'nivel' => 5,
            'es_movimiento' => false,
            'moneda_principal' => "BOB",
            'parent_id' => $impuestosMunicipales->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'PATENTES MUNICIPALES',
            'tipo_cuenta' => 'Pasivo',
            'nivel' => 5,
            'es_movimiento' => false,
            'moneda_principal' => "BOB",
            'parent_id' => $impuestosMunicipales->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

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

        $prestamosBancarios = CuentasContables::create([
            'nombre_cuenta' => 'PRÉSTAMOS BANCARIOS',
            'tipo_cuenta' => 'Pasivo',
            'nivel' => 4,
            'es_movimiento' => true,
            'parent_id' => $deudasLargoPlazo->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'PRÉSTAMOS BANCARIOS M/N',
            'tipo_cuenta' => 'Pasivo',
            'nivel' => 5,
            'es_movimiento' => true,
            'moneda_principal' => "BOB",
            'parent_id' => $prestamosBancarios->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'PRÉSTAMOS BANCARIOS M/E',
            'tipo_cuenta' => 'Pasivo',
            'nivel' => 5,
            'es_movimiento' => true,
            'moneda_principal' => "USD",
            'parent_id' => $prestamosBancarios->id_cuenta,
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
            'moneda_principal' => "BOB",
            'parent_id' => $capitalPagado->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'CAPITAL NO PAGADO',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 4,
            'es_movimiento' => true,
            'moneda_principal' => "BOB",
            'parent_id' => $capitalPagado->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $reservas = CuentasContables::create([
            'nombre_cuenta' => 'RESERVAS',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 2,
            'es_movimiento' => false,
            'parent_id' => $patrimonio->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $reservasContables = CuentasContables::create([
            'nombre_cuenta' => 'RESERVAS',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 3,
            'es_movimiento' => false,
            'parent_id' => $reservas->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $cuentasReservasContables = [
            'RESERVA LEGAL',
            'RESERVA ESTATUTARIA',
            'RESERVA OCASIONAL',
            'RESERVA POR REVALUACIÓN',
        ];

        foreach ($cuentasReservasContables as $nombre) {
            CuentasContables::create([
                'nombre_cuenta' => $nombre,
                'tipo_cuenta' => 'Patrimonio',
                'nivel' => 4,
                'es_movimiento' => true,
                'moneda_principal' => "BOB",
                'parent_id' => $reservasContables->id_cuenta,
                'empresa_id' => $empresa->id,
            ]);
        }

        $resultadosAcumulados = CuentasContables::create([
            'nombre_cuenta' => 'RESULTADOS ACUMULADOS',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 2,
            'es_movimiento' => false,
            'parent_id' => $patrimonio->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $resultadosDeEjerciciosAnterios = CuentasContables::create([
            'nombre_cuenta' => 'RESULTADOS DE EJERCICIOS ANTERIORES',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 3,
            'es_movimiento' => true,
            'parent_id' => $resultadosAcumulados->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'RESULTADOS DE EJERCICIOS ANTERIORES',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 4,
            'es_movimiento' => true,
            'moneda_principal' => "BOB",
            'parent_id' => $resultadosDeEjerciciosAnterios->id_cuenta,
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

        $aportesDeCapital = CuentasContables::create([
            'nombre_cuenta' => 'APORTES DE CAPITAL',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 3,
            'es_movimiento' => false,
            'parent_id' => $patrimonioAportado->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $componentesDeCapital = CuentasContables::create([
            'nombre_cuenta' => 'COMPONENTES DE CAPITAL',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 4,
            'es_movimiento' => false,
            'parent_id' => $aportesDeCapital->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'CAPITAL SUSCRITO',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 5,
            'es_movimiento' => true,
            'moneda_principal' => "BOB",
            'parent_id' => $componentesDeCapital->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'CAPITAL INTEGRADO',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 5,
            'es_movimiento' => true,
            'moneda_principal' => "BOB",
            'parent_id' => $componentesDeCapital->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'PRIMAS DE EMISIÓN',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 5,
            'es_movimiento' => false,
            'moneda_principal' => "BOB",
            'parent_id' => $componentesDeCapital->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'PRIMAS DE SUSCRIPCIÓN',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 5,
            'es_movimiento' => true,
            'moneda_principal' => "BOB",
            'parent_id' => $componentesDeCapital->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $excedentes = CuentasContables::create([
            'nombre_cuenta' => 'EXCEDENTES',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 2,
            'es_movimiento' => false,
            'parent_id' => $patrimonio->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $excedentesContables = CuentasContables::create([
            'nombre_cuenta' => 'EXCEDENTES CONTABLES',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 3,
            'es_movimiento' => false,
            'parent_id' => $excedentes->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $componentesDeExcedentes = CuentasContables::create([
            'nombre_cuenta' => 'COMPONENTES DE EXCEDENTES',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 4,
            'es_movimiento' => false,
            'parent_id' => $excedentesContables->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'EXCEDENTES ACUMULADOS',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 5,
            'es_movimiento' => true,
            'moneda_principal' => "BOB",
            'parent_id' => $componentesDeExcedentes->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'EXCEDENTES DEL EJERCICIO',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 5,
            'es_movimiento' => true,
            'moneda_principal' => "BOB",
            'parent_id' => $componentesDeExcedentes->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'EXCEDENTES NO DISTRIBUIDOS',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 5,
            'es_movimiento' => true,
            'moneda_principal' => "BOB",
            'parent_id' => $componentesDeExcedentes->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $aportesDeSocios = CuentasContables::create([
            'nombre_cuenta' => 'APORTES DE SOCIOS',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 2,
            'es_movimiento' => false,
            'parent_id' => $patrimonio->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $aportesDirectos = CuentasContables::create([
            'nombre_cuenta' => 'APORTES DIRECTOS',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 3,
            'es_movimiento' => false,
            'parent_id' => $aportesDeSocios->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $componentesDeAportes = CuentasContables::create([
            'nombre_cuenta' => 'COMPONENTES DE APORTES',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 4,
            'es_movimiento' => false,
            'parent_id' => $aportesDirectos->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'APORTES DE CAPITAL',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 5,
            'es_movimiento' => true,
            'moneda_principal' => "BOB",
            'parent_id' => $componentesDeAportes->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'APORTES ADICIONALES',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 5,
            'es_movimiento' => true,
            'moneda_principal' => "BOB",
            'parent_id' => $componentesDeAportes->id_cuenta,
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

        $ventasPorProductos = CuentasContables::create([
            'nombre_cuenta' => 'VENTAS POR PRODUCTOS',
            'tipo_cuenta' => 'Ingresos',
            'nivel' => 4,
            'es_movimiento' => false,
            'parent_id' => $ventas->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $cuentasVentasPorProductos = [
            'VENTAS PRODUCTOS TERMINADOS',
            'VENTAS MERCADERÍAS',
            'VENTAS SERVICIOS',
        ];

        foreach ($cuentasVentasPorProductos as $nombre) {
            CuentasContables::create([
                'nombre_cuenta' => $nombre,
                'tipo_cuenta' => 'Ingresos',
                'nivel' => 5,
                'es_movimiento' => true,
                'moneda_principal' => "BOB",
                'parent_id' => $ventasPorProductos->id_cuenta,
                'empresa_id' => $empresa->id,
            ]);
        }

        $devoluciones = CuentasContables::create([
            'nombre_cuenta' => 'DEVOLUCIONES',
            'tipo_cuenta' => 'Ingresos',
            'nivel' => 4,
            'es_movimiento' => false,
            'parent_id' => $ventas->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'DEVOLUCIONES SOBRE VENTAS',
            'tipo_cuenta' => 'Ingresos',
            'nivel' => 5,
            'es_movimiento' => false,
            'moneda_principal' => "BOB",
            'parent_id' => $devoluciones->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

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

        $ingresosPorInteresYRentas = CuentasContables::create([
            'nombre_cuenta' => 'INGRESOS POR INTERES Y RENTAS',
            'tipo_cuenta' => 'Ingresos',
            'nivel' => 4,
            'es_movimiento' => false,
            'parent_id' => $ingresosFinancieros->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $cuentasIngresosPorInteresYRentas = [
            'INTERESES BANCARIOS',
            'RENTAS SOBRE INVERSIONES',
        ];

        foreach ($cuentasIngresosPorInteresYRentas as $nombre) {
            CuentasContables::create([
                'nombre_cuenta' => $nombre,
                'tipo_cuenta' => 'Ingresos',
                'nivel' => 5,
                'es_movimiento' => true,
                'moneda_principal' => "BOB",
                'parent_id' => $ingresosPorInteresYRentas->id_cuenta,
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

        $ingresosNoRecurrentes = CuentasContables::create([
            'nombre_cuenta' => 'INGRESOS NO RECURRENTES',
            'tipo_cuenta' => 'Ingresos',
            'nivel' => 4,
            'es_movimiento' => false,
            'parent_id' => $ingresosExtraordinarios->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $cuentasIngresosNoRecurrentes = [
            'VENTA DE ACTIVOS FIJOS',
            'RENTAS EXTRAORDINARIAS',
        ];

        foreach ($cuentasIngresosNoRecurrentes as $nombre) {
            CuentasContables::create([
                'nombre_cuenta' => $nombre,
                'tipo_cuenta' => 'Ingresos',
                'nivel' => 5,
                'es_movimiento' => true,
                'moneda_principal' => "BOB",
                'parent_id' => $ingresosNoRecurrentes->id_cuenta,
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
                'moneda_principal' => "BOB",
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

        $serviciosPrestados = CuentasContables::create([
            'nombre_cuenta' => 'SERVICIOS PRESTADOS',
            'tipo_cuenta' => 'Ingresos',
            'nivel' => 4,
            'es_movimiento' => false,
            'parent_id' => $ingresosServicios->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $cuentasServiciosPrestados = [
            'HONORARIOS POR SERVICIOS',
            'COMISIONES POR SERVICIOS',
        ];

        foreach ($cuentasServiciosPrestados as $nombre) {
            CuentasContables::create([
                'nombre_cuenta' => $nombre,
                'tipo_cuenta' => 'Ingresos',
                'nivel' => 5,
                'es_movimiento' => true,
                'parent_id' => $serviciosPrestados->id_cuenta,
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

        $componentesDelCosto = CuentasContables::create([
            'nombre_cuenta' => 'COSTO DE PRODUCTOS VENDIDOS',
            'tipo_cuenta' => 'Egresos',
            'nivel' => 4,
            'es_movimiento' => false,
            'parent_id' => $costoProductosVendidos->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $cuentasComponentesDelCosto = [
            'MATERIALES DIRECTOS',
            'MANO DE OBRA DIRECTA',
            'COSTOS INDIRECTOS DE FABRICACIÓN',
        ];

        foreach ($cuentasComponentesDelCosto as $nombre) {
            CuentasContables::create([
                'nombre_cuenta' => $nombre,
                'tipo_cuenta' => 'Egresos',
                'nivel' => 5,
                'es_movimiento' => true,
                'moneda_principal' => "BOB",
                'parent_id' => $componentesDelCosto->id_cuenta,
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

        $componentesAdministrativos = CuentasContables::create([
            'nombre_cuenta' => 'COMPONENTES ADMINISTRATIVOS',
            'tipo_cuenta' => 'Egresos',
            'nivel' => 4,
            'es_movimiento' => false,
            'parent_id' => $gastosAdministracion->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $cuentasComponentesAdministrativos = [
            'SUELDOS Y SALARIOS',
            'ARRENDAMIENTOS',
            'GASTOS DE OFICINA',
        ];

        foreach ($cuentasComponentesAdministrativos as $nombre) {
            CuentasContables::create([
                'nombre_cuenta' => $nombre,
                'tipo_cuenta' => 'Egresos',
                'nivel' => 5,
                'es_movimiento' => true,
                'moneda_principal' => "BOB",
                'parent_id' => $componentesAdministrativos->id_cuenta,
                'empresa_id' => $empresa->id,
            ]);
        }

        // -------------------- GASTOS DE VENTA --------------------
        $gastosVenta = CuentasContables::create([
            'nombre_cuenta' => 'GASTOS DE VENTA',
            'tipo_cuenta' => 'Egresos',
            'nivel' => 3,
            'es_movimiento' => false,
            'parent_id' => $gastosOperativos->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $componentesDeVenta = CuentasContables::create([
            'nombre_cuenta' => 'COMPONENTES DE VENTA',
            'tipo_cuenta' => 'Egresos',
            'nivel' => 4,
            'es_movimiento' => false,
            'parent_id' => $gastosVenta->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'PUBLICIDAD Y PROMOCIÓN',
            'tipo_cuenta' => 'Egresos',
            'nivel' => 5,
            'es_movimiento' => false,
            'moneda_principal' => "BOB",
            'parent_id' => $componentesDeVenta->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'ANUNCIOS PUBLICITARIOS',
            'tipo_cuenta' => 'Egresos',
            'nivel' => 5,
            'es_movimiento' => false,
            'moneda_principal' => "BOB",
            'parent_id' => $componentesDeVenta->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'GASTOS DE PROMOCIÓN',
            'tipo_cuenta' => 'Egresos',
            'nivel' => 5,
            'es_movimiento' => false,
            'moneda_principal' => "BOB",
            'parent_id' => $componentesDeVenta->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        // -------------------- GASTOS FINANCIEROS --------------------
        $gastosFinancieros = CuentasContables::create([
            'nombre_cuenta' => 'GASTOS FINANCIEROS',
            'tipo_cuenta' => 'Egresos',
            'nivel' => 2,
            'es_movimiento' => false,
            'parent_id' => $egresos->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $interesesYCargosFinancieros = CuentasContables::create([
            'nombre_cuenta' => 'INTERESES Y CARGOS FINANCIEROS',
            'tipo_cuenta' => 'Egresos',
            'nivel' => 3,
            'es_movimiento' => false,
            'parent_id' => $gastosFinancieros->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $componentesDeInteres = CuentasContables::create([
            'nombre_cuenta' => 'COMPONENTES DE INTERES',
            'tipo_cuenta' => 'Egresos',
            'nivel' => 4,
            'es_movimiento' => false,
            'parent_id' => $interesesYCargosFinancieros->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $cuentasComponentesDeInteres = [
            'INTERESES PAGADOS',
            'INTERESES BANCARIOS',
            'INTERESES DE DOCUMENTOS POR PAGAR',
        ];

        foreach ($cuentasComponentesDeInteres as $nombre) {
            CuentasContables::create([
                'nombre_cuenta' => $nombre,
                'tipo_cuenta' => 'Egresos',
                'nivel' => 5,
                'es_movimiento' => true,
                'moneda_principal' => "BOB",
                'parent_id' => $componentesDeInteres->id_cuenta,
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

        $componentesDePerdida = CuentasContables::create([
            'nombre_cuenta' => 'PÉRDIDAS EXTRAORDINARIAS',
            'tipo_cuenta' => 'Egresos',
            'nivel' => 4,
            'es_movimiento' => false,
            'parent_id' => $perdidasExtraordinarias->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $cuentasComponentesDePerdida = [
            'PÉRDIDAS POR VENTA DE ACTIVOS FIJOS',
            'PÉRDIDAS POR ROBO O DAÑOS',
        ];

        foreach ($cuentasComponentesDePerdida as $nombre) {
            CuentasContables::create([
                'nombre_cuenta' => $nombre,
                'tipo_cuenta' => 'Egresos',
                'nivel' => 5,
                'es_movimiento' => true,
                'moneda_principal' => "BOB",
                'parent_id' => $componentesDePerdida->id_cuenta,
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
