<?php

namespace Database\Seeders;

use App\Http\Controllers\EmpresasController;
use App\Models\CuentasContables;
use App\Models\Empresa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CuentasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ACTIVO
        $activo = CuentasContables::create([
            'codigo_cuenta' => '1000000000',
            'nombre_cuenta' => 'ACTIVO',
            'tipo_cuenta' => 'Activo',
            'nivel' => 1,
            'parent_id' => null,
            'es_movimiento' => false,
        ]);

        $activo_corriente = CuentasContables::create([
            'codigo_cuenta' => '1100000000',
            'nombre_cuenta' => 'ACTIVO CORRIENTE',
            'tipo_cuenta' => 'Activo',
            'nivel' => 2,
            'parent_id' => $activo->id_cuenta,
            'es_movimiento' => false,
        ]);

        $disponibilidades = CuentasContables::create([
            'codigo_cuenta' => '1101000000',
            'nombre_cuenta' => 'DISPONIBILIDADES',
            'tipo_cuenta' => 'Activo',
            'nivel' => 3,
            'parent_id' => $activo_corriente->id_cuenta,
            'es_movimiento' => false,
        ]);

        CuentasContables::create([
            'codigo_cuenta' => '1101010000',
            'nombre_cuenta' => 'CAJA MONEDA NACIONAL',
            'tipo_cuenta' => 'Activo',
            'nivel' => 4,
            'parent_id' => $disponibilidades->id_cuenta,
            'es_movimiento' => true,
            'moneda_principal' => 'BOB', // ✅
        ]);

        CuentasContables::create([
            'codigo_cuenta' => '1101020000',
            'nombre_cuenta' => 'CAJA MONEDA EXTRANJERA',
            'tipo_cuenta' => 'Activo',
            'nivel' => 4,
            'parent_id' => $disponibilidades->id_cuenta,
            'es_movimiento' => true,
            'moneda_principal' => 'USD', // ✅
        ]);

        CuentasContables::create([
            'codigo_cuenta' => '1101030000',
            'nombre_cuenta' => 'BANCOS MONEDA NACIONAL',
            'tipo_cuenta' => 'Activo',
            'nivel' => 4,
            'parent_id' => $disponibilidades->id_cuenta,
            'es_movimiento' => true,
            'moneda_principal' => 'BOB', // ✅
        ]);

        CuentasContables::create([
            'codigo_cuenta' => '1101040000',
            'nombre_cuenta' => 'BANCOS MONEDA EXTRANJERA',
            'tipo_cuenta' => 'Activo',
            'nivel' => 4,
            'parent_id' => $disponibilidades->id_cuenta,
            'es_movimiento' => true,
            'moneda_principal' => 'USD', // ✅
        ]);

        // PASIVO
        $pasivo = CuentasContables::create([
            'codigo_cuenta' => '2000000000',
            'nombre_cuenta' => 'PASIVO',
            'tipo_cuenta' => 'Pasivo',
            'nivel' => 1,
            'parent_id' => null,
            'es_movimiento' => false,
        ]);

        $pasivo_corriente = CuentasContables::create([
            'codigo_cuenta' => '2100000000',
            'nombre_cuenta' => 'PASIVO CORRIENTE',
            'tipo_cuenta' => 'Pasivo',
            'nivel' => 2,
            'parent_id' => $pasivo->id_cuenta,
            'es_movimiento' => false,
        ]);

        CuentasContables::create([
            'codigo_cuenta' => '2101000000',
            'nombre_cuenta' => 'CUENTAS POR PAGAR',
            'tipo_cuenta' => 'Pasivo',
            'nivel' => 3,
            'parent_id' => $pasivo_corriente->id_cuenta,
            'es_movimiento' => false,
        ]);

        CuentasContables::create([
            'codigo_cuenta' => '2101010000',
            'nombre_cuenta' => 'CUENTAS POR PAGAR A PROVEEDORES',
            'tipo_cuenta' => 'Pasivo',
            'nivel' => 4,
            'parent_id' => $pasivo_corriente->id_cuenta,
            'es_movimiento' => true,
            'moneda_principal' => 'BOB', // ✅
        ]);

        // PATRIMONIO
        $patrimonio = CuentasContables::create([
            'codigo_cuenta' => '3000000000',
            'nombre_cuenta' => 'PATRIMONIO',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 1,
            'parent_id' => null,
            'es_movimiento' => false,
        ]);

        CuentasContables::create([
            'codigo_cuenta' => '3100000000',
            'nombre_cuenta' => 'CAPITAL SOCIAL',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 2,
            'parent_id' => $patrimonio->id_cuenta,
            'es_movimiento' => true,
        ]);

        // INGRESOS
        $ingresos = CuentasContables::create([
            'codigo_cuenta' => '4000000000',
            'nombre_cuenta' => 'INGRESOS',
            'tipo_cuenta' => 'Ingresos',
            'nivel' => 1,
            'parent_id' => null,
            'es_movimiento' => false,
        ]);

        CuentasContables::create([
            'codigo_cuenta' => '4100000000',
            'nombre_cuenta' => 'INGRESOS OPERACIONALES',
            'tipo_cuenta' => 'Ingresos',
            'nivel' => 2,
            'parent_id' => $ingresos->id_cuenta,
            'es_movimiento' => false,
        ]);

        CuentasContables::create([
            'codigo_cuenta' => '4101000000',
            'nombre_cuenta' => 'VENTAS',
            'tipo_cuenta' => 'Ingresos',
            'nivel' => 3,
            'parent_id' => $ingresos->id_cuenta,
            'es_movimiento' => true,
        ]);

        // EGRESOS
        $egresos = CuentasContables::create([
            'codigo_cuenta' => '5000000000',
            'nombre_cuenta' => 'EGRESOS',
            'tipo_cuenta' => 'Egresos',
            'nivel' => 1,
            'parent_id' => null,
            'es_movimiento' => false,
        ]);

        CuentasContables::create([
            'codigo_cuenta' => '5100000000',
            'nombre_cuenta' => 'EGRESOS OPERACIONALES',
            'tipo_cuenta' => 'Egresos',
            'nivel' => 2,
            'parent_id' => $egresos->id_cuenta,
            'es_movimiento' => false,
        ]);

        CuentasContables::create([
            'codigo_cuenta' => '5101000000',
            'nombre_cuenta' => 'GASTOS DE ADMINISTRACIÓN',
            'tipo_cuenta' => 'Egresos',
            'nivel' => 2,
            'parent_id' => $egresos->id_cuenta,
            'es_movimiento' => true,
        ]);
    }


    /**
     * Seed cuentas contables para una empresa específica.
     */
    public function seedForEmpresa(Empresa $empresa): void
    {
        // EmpresasController::crearCuentasActivos($empresa);
        // ACTIVO
        $activo = CuentasContables::create([
            'codigo_cuenta' => '1000000000',
            'nombre_cuenta' => 'ACTIVO',
            'tipo_cuenta' => 'Activo',
            'nivel' => 1,
            'parent_id' => null,
            'es_movimiento' => false,
            'empresa_id' => $empresa->id,
        ]);

        $activo_corriente = CuentasContables::create([
            'codigo_cuenta' => '1100000000',
            'nombre_cuenta' => 'ACTIVO CORRIENTE',
            'tipo_cuenta' => 'Activo',
            'nivel' => 2,
            'parent_id' => $activo->id_cuenta,
            'es_movimiento' => false,
            'empresa_id' => $empresa->id,
        ]);

        $disponibilidades = CuentasContables::create([
            'codigo_cuenta' => '1101000000',
            'nombre_cuenta' => 'DISPONIBILIDADES',
            'tipo_cuenta' => 'Activo',
            'nivel' => 3,
            'parent_id' => $activo_corriente->id_cuenta,
            'es_movimiento' => false,
            'empresa_id' => $empresa->id,
        ]);

        $this->crearMovimiento($empresa, '1101010000', 'CAJA MONEDA NACIONAL', $disponibilidades->id_cuenta, 'BOB');
        $this->crearMovimiento($empresa, '1101020000', 'CAJA MONEDA EXTRANJERA', $disponibilidades->id_cuenta, 'USD');
        $this->crearMovimiento($empresa, '1101030000', 'BANCOS MONEDA NACIONAL', $disponibilidades->id_cuenta, 'BOB');
        $this->crearMovimiento($empresa, '1101040000', 'BANCOS MONEDA EXTRANJERA', $disponibilidades->id_cuenta, 'USD');

        // PASIVO
        $pasivo = CuentasContables::create([
            'codigo_cuenta' => '2000000000',
            'nombre_cuenta' => 'PASIVO',
            'tipo_cuenta' => 'Pasivo',
            'nivel' => 1,
            'parent_id' => null,
            'es_movimiento' => false,
            'empresa_id' => $empresa->id,
        ]);

        $pasivo_corriente = CuentasContables::create([
            'codigo_cuenta' => '2100000000',
            'nombre_cuenta' => 'PASIVO CORRIENTE',
            'tipo_cuenta' => 'Pasivo',
            'nivel' => 2,
            'parent_id' => $pasivo->id_cuenta,
            'es_movimiento' => false,
            'empresa_id' => $empresa->id,
        ]);

        $cuentas_por_pagar = CuentasContables::create([
            'codigo_cuenta' => '2101000000',
            'nombre_cuenta' => 'CUENTAS POR PAGAR',
            'tipo_cuenta' => 'Pasivo',
            'nivel' => 3,
            'parent_id' => $pasivo_corriente->id_cuenta,
            'es_movimiento' => false,
            'empresa_id' => $empresa->id,
        ]);

        $this->crearMovimiento($empresa, '2101010000', 'CUENTAS POR PAGAR A PROVEEDORES', $cuentas_por_pagar->id_cuenta, 'BOB');

        // PATRIMONIO
        $patrimonio = CuentasContables::create([
            'codigo_cuenta' => '3000000000',
            'nombre_cuenta' => 'PATRIMONIO',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 1,
            'parent_id' => null,
            'es_movimiento' => false,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'codigo_cuenta' => '3100000000',
            'nombre_cuenta' => 'CAPITAL SOCIAL',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 2,
            'parent_id' => $patrimonio->id_cuenta,
            'es_movimiento' => true,
            'empresa_id' => $empresa->id,
        ]);

        // INGRESOS
        $ingresos = CuentasContables::create([
            'codigo_cuenta' => '4000000000',
            'nombre_cuenta' => 'INGRESOS',
            'tipo_cuenta' => 'Ingresos',
            'nivel' => 1,
            'parent_id' => null,
            'es_movimiento' => false,
            'empresa_id' => $empresa->id,
        ]);

        $ingresos_operacionales = CuentasContables::create([
            'codigo_cuenta' => '4100000000',
            'nombre_cuenta' => 'INGRESOS OPERACIONALES',
            'tipo_cuenta' => 'Ingresos',
            'nivel' => 2,
            'parent_id' => $ingresos->id_cuenta,
            'es_movimiento' => false,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'codigo_cuenta' => '4101000000',
            'nombre_cuenta' => 'VENTAS',
            'tipo_cuenta' => 'Ingresos',
            'nivel' => 3,
            'parent_id' => $ingresos_operacionales->id_cuenta,
            'es_movimiento' => true,
            'empresa_id' => $empresa->id,
        ]);

        // EGRESOS
        $egresos = CuentasContables::create([
            'codigo_cuenta' => '5000000000',
            'nombre_cuenta' => 'EGRESOS',
            'tipo_cuenta' => 'Egresos',
            'nivel' => 1,
            'parent_id' => null,
            'es_movimiento' => false,
            'empresa_id' => $empresa->id,
        ]);

        $egresos_operacionales = CuentasContables::create([
            'codigo_cuenta' => '5100000000',
            'nombre_cuenta' => 'EGRESOS OPERACIONALES',
            'tipo_cuenta' => 'Egresos',
            'nivel' => 2,
            'parent_id' => $egresos->id_cuenta,
            'es_movimiento' => false,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'codigo_cuenta' => '5101000000',
            'nombre_cuenta' => 'GASTOS DE ADMINISTRACIÓN',
            'tipo_cuenta' => 'Egresos',
            'nivel' => 2,
            'parent_id' => $egresos_operacionales->id_cuenta,
            'es_movimiento' => true,
            'empresa_id' => $empresa->id,
        ]);
    }

    /**
     * Método auxiliar para crear una cuenta de movimiento.
     */
    protected static function crearMovimiento(Empresa $empresa, string $codigo, string $nombre, int $parentId, string $moneda): void
    {
        CuentasContables::create([
            'codigo_cuenta' => $codigo,
            'nombre_cuenta' => $nombre,
            'tipo_cuenta' => 'Activo', // Se puede parametrizar si lo necesitas más adelante
            'nivel' => 4,
            'parent_id' => $parentId,
            'es_movimiento' => true,
            'moneda_principal' => $moneda,
            'empresa_id' => $empresa->id,
        ]);
    }



    // SEEDERS FOR EMPRESA



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

    public static function crearCuentasPasivos(Empresa $empresa)
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

    public static function crearCuentasPatrimonio(Empresa $empresa)
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
            'nombre_cuenta' => 'RESERVAS CONTABLES',
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

        $resultadosDeEjercicios = CuentasContables::create([
            'nombre_cuenta' => 'RESULTADO DE EJERCICIOS',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 3,
            'es_movimiento' => true,
            'parent_id' => $resultadosAcumulados->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'RESULTADO DE EJERCICIOS',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 4,
            'es_movimiento' => true,
            'moneda_principal' => "BOB",
            'parent_id' => $resultadosDeEjercicios->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $resultadosDeEjerciciosAnterios = CuentasContables::create([
            'nombre_cuenta' => 'RESULTADO DE EJERCICIOS ANTERIORES',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 3,
            'es_movimiento' => true,
            'parent_id' => $resultadosAcumulados->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'RESULTADO DE EJERCICIOS ANTERIORES',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 4,
            'es_movimiento' => true,
            'moneda_principal' => "BOB",
            'parent_id' => $resultadosDeEjerciciosAnterios->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);
    }

    public static function crearCuentasIngresos(Empresa $empresa)
    {
        $ingresos = CuentasContables::create([
            'nombre_cuenta' => 'INGRESOS',
            'tipo_cuenta' => 'Ingreso',
            'codigo_cuenta' => self::generarCodigoRaiz('Ingresos'),
            'nivel' => 1,
            'es_movimiento' => false,
            'empresa_id' => $empresa->id,
        ]);

        // -------------------- INGRESOS OPERATIVOS --------------------
        $ingresosOperativos = CuentasContables::create([
            'nombre_cuenta' => 'INGRESOS OPERATIVOS',
            'tipo_cuenta' => 'Ingreso',
            'nivel' => 2,
            'es_movimiento' => false,
            'parent_id' => $ingresos->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $ventas = CuentasContables::create([
            'nombre_cuenta' => 'VENTAS',
            'tipo_cuenta' => 'Ingreso',
            'nivel' => 3,
            'es_movimiento' => false,
            'parent_id' => $ingresosOperativos->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $ventasPorProductos = CuentasContables::create([
            'nombre_cuenta' => 'VENTAS POR PRODUCTOS',
            'tipo_cuenta' => 'Ingreso',
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
                'tipo_cuenta' => 'Ingreso',
                'nivel' => 5,
                'es_movimiento' => true,
                'moneda_principal' => "BOB",
                'parent_id' => $ventasPorProductos->id_cuenta,
                'empresa_id' => $empresa->id,
            ]);
        }

        $devoluciones = CuentasContables::create([
            'nombre_cuenta' => 'DEVOLUCIONES',
            'tipo_cuenta' => 'Ingreso',
            'nivel' => 4,
            'es_movimiento' => false,
            'parent_id' => $ventas->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'DEVOLUCIONES SOBRE VENTAS',
            'tipo_cuenta' => 'Ingreso',
            'nivel' => 5,
            'es_movimiento' => false,
            'moneda_principal' => "BOB",
            'parent_id' => $devoluciones->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        // -------------------- INGRESOS NO OPERATIVOS --------------------
        $ingresosNoOperativos = CuentasContables::create([
            'nombre_cuenta' => 'INGRESOS NO OPERATIVOS',
            'tipo_cuenta' => 'Ingreso',
            'nivel' => 2,
            'es_movimiento' => false,
            'parent_id' => $ingresos->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $ingresosFinancieros = CuentasContables::create([
            'nombre_cuenta' => 'INGRESOS FINANCIEROS',
            'tipo_cuenta' => 'Ingreso',
            'nivel' => 3,
            'es_movimiento' => false,
            'parent_id' => $ingresosNoOperativos->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $ingresosPorInteresYRentas = CuentasContables::create([
            'nombre_cuenta' => 'INGRESOS POR INTERES Y RENTAS',
            'tipo_cuenta' => 'Ingreso',
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
                'tipo_cuenta' => 'Ingreso',
                'nivel' => 5,
                'es_movimiento' => true,
                'moneda_principal' => "BOB",
                'parent_id' => $ingresosPorInteresYRentas->id_cuenta,
                'empresa_id' => $empresa->id,
            ]);
        }

        $ingresosExtraordinarios = CuentasContables::create([
            'nombre_cuenta' => 'INGRESOS EXTRAORDINARIOS',
            'tipo_cuenta' => 'Ingreso',
            'nivel' => 3,
            'es_movimiento' => false,
            'parent_id' => $ingresosNoOperativos->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $ingresosNoRecurrentes = CuentasContables::create([
            'nombre_cuenta' => 'INGRESOS NO RECURRENTES',
            'tipo_cuenta' => 'Ingreso',
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
                'tipo_cuenta' => 'Ingreso',
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
            'tipo_cuenta' => 'Ingreso',
            'nivel' => 2,
            'es_movimiento' => false,
            'parent_id' => $ingresos->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $donaciones = CuentasContables::create([
            'nombre_cuenta' => 'DONACIONES',
            'tipo_cuenta' => 'Ingreso',
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
                'tipo_cuenta' => 'Ingreso',
                'nivel' => 4,
                'es_movimiento' => true,
                'moneda_principal' => "BOB",
                'parent_id' => $donaciones->id_cuenta,
                'empresa_id' => $empresa->id,
            ]);
        }

        $ingresosServicios = CuentasContables::create([
            'nombre_cuenta' => 'INGRESOS POR SERVICIOS',
            'tipo_cuenta' => 'Ingreso',
            'nivel' => 3,
            'es_movimiento' => false,
            'parent_id' => $otrosIngresos->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $serviciosPrestados = CuentasContables::create([
            'nombre_cuenta' => 'SERVICIOS PRESTADOS',
            'tipo_cuenta' => 'Ingreso',
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
                'tipo_cuenta' => 'Ingreso',
                'nivel' => 5,
                'es_movimiento' => true,
                'parent_id' => $serviciosPrestados->id_cuenta,
                'empresa_id' => $empresa->id,
            ]);
        }
    }

    public static function crearCuentasEgresos(Empresa $empresa)
    {
        $egresos = CuentasContables::create([
            'nombre_cuenta' => 'EGRESOS',
            'tipo_cuenta' => 'Egreso',
            'codigo_cuenta' => self::generarCodigoRaiz('Egresos'),
            'nivel' => 1,
            'es_movimiento' => false,
            'empresa_id' => $empresa->id,
        ]);

        // -------------------- COSTO DE VENTAS --------------------
        $costoVentas = CuentasContables::create([
            'nombre_cuenta' => 'COSTO DE VENTAS',
            'tipo_cuenta' => 'Egreso',
            'nivel' => 2,
            'es_movimiento' => false,
            'parent_id' => $egresos->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $costoProductosVendidos = CuentasContables::create([
            'nombre_cuenta' => 'COSTO DE PRODUCTOS VENDIDOS',
            'tipo_cuenta' => 'Egreso',
            'nivel' => 3,
            'es_movimiento' => false,
            'parent_id' => $costoVentas->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        // -------------------- GASTOS OPERATIVOS --------------------
        $gastosOperativos = CuentasContables::create([
            'nombre_cuenta' => 'GASTOS OPERATIVOS',
            'tipo_cuenta' => 'Egreso',
            'nivel' => 2,
            'es_movimiento' => false,
            'parent_id' => $egresos->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $gastosAdministracion = CuentasContables::create([
            'nombre_cuenta' => 'GASTOS DE ADMINISTRACIÓN',
            'tipo_cuenta' => 'Egreso',
            'nivel' => 3,
            'es_movimiento' => false,
            'parent_id' => $gastosOperativos->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $componentesAdministrativos = CuentasContables::create([
            'nombre_cuenta' => 'COMPONENTES ADMINISTRATIVOS',
            'tipo_cuenta' => 'Egreso',
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
                'tipo_cuenta' => 'Egreso',
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
            'tipo_cuenta' => 'Egreso',
            'nivel' => 3,
            'es_movimiento' => false,
            'parent_id' => $gastosOperativos->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $componentesDeVenta = CuentasContables::create([
            'nombre_cuenta' => 'COMPONENTES DE VENTA',
            'tipo_cuenta' => 'Egreso',
            'nivel' => 4,
            'es_movimiento' => false,
            'parent_id' => $gastosVenta->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'PUBLICIDAD Y PROMOCIÓN',
            'tipo_cuenta' => 'Egreso',
            'nivel' => 5,
            'es_movimiento' => false,
            'moneda_principal' => "BOB",
            'parent_id' => $componentesDeVenta->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'ANUNCIOS PUBLICITARIOS',
            'tipo_cuenta' => 'Egreso',
            'nivel' => 5,
            'es_movimiento' => false,
            'moneda_principal' => "BOB",
            'parent_id' => $componentesDeVenta->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        CuentasContables::create([
            'nombre_cuenta' => 'GASTOS DE PROMOCIÓN',
            'tipo_cuenta' => 'Egreso',
            'nivel' => 5,
            'es_movimiento' => false,
            'moneda_principal' => "BOB",
            'parent_id' => $componentesDeVenta->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        // -------------------- GASTOS FINANCIEROS --------------------
        $gastosFinancieros = CuentasContables::create([
            'nombre_cuenta' => 'GASTOS FINANCIEROS',
            'tipo_cuenta' => 'Egreso',
            'nivel' => 2,
            'es_movimiento' => false,
            'parent_id' => $egresos->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $interesesYCargosFinancieros = CuentasContables::create([
            'nombre_cuenta' => 'INTERESES Y CARGOS FINANCIEROS',
            'tipo_cuenta' => 'Egreso',
            'nivel' => 3,
            'es_movimiento' => false,
            'parent_id' => $gastosFinancieros->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $componentesDeInteres = CuentasContables::create([
            'nombre_cuenta' => 'COMPONENTES DE INTERES',
            'tipo_cuenta' => 'Egreso',
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
                'tipo_cuenta' => 'Egreso',
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
            'tipo_cuenta' => 'Egreso',
            'nivel' => 2,
            'es_movimiento' => false,
            'parent_id' => $egresos->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $perdidasExtraordinarias = CuentasContables::create([
            'nombre_cuenta' => 'PÉRDIDAS EXTRAORDINARIAS',
            'tipo_cuenta' => 'Egreso',
            'nivel' => 3,
            'es_movimiento' => false,
            'parent_id' => $otrosGastos->id_cuenta,
            'empresa_id' => $empresa->id,
        ]);

        $componentesDePerdida = CuentasContables::create([
            'nombre_cuenta' => 'COMPONENTES DE PERDIDA',
            'tipo_cuenta' => 'Egreso',
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
                'tipo_cuenta' => 'Egreso',
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
}
