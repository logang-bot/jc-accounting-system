<?php

namespace Database\Seeders;

use App\Models\CuentasContables;
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
        ]);

        CuentasContables::create([
            'codigo_cuenta' => '1101020000',
            'nombre_cuenta' => 'CAJA MONEDA EXTRANJERA',
            'tipo_cuenta' => 'Activo',
            'nivel' => 4,
            'parent_id' => $disponibilidades->id_cuenta,
            'es_movimiento' => true,
        ]);

        CuentasContables::create([
            'codigo_cuenta' => '1101030000',
            'nombre_cuenta' => 'BANCOS MONEDA NACIONAL',
            'tipo_cuenta' => 'Activo',
            'nivel' => 4,
            'parent_id' => $disponibilidades->id_cuenta,
            'es_movimiento' => true,
        ]);

        CuentasContables::create([
            'codigo_cuenta' => '1101040000',
            'nombre_cuenta' => 'BANCOS MONEDA EXTRANJERA',
            'tipo_cuenta' => 'Activo',
            'nivel' => 4,
            'parent_id' => $disponibilidades->id_cuenta,
            'es_movimiento' => true,
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
            'nivel' => 3,
            'parent_id' => $egresos->id_cuenta,
            'es_movimiento' => true,
        ]);
    }
}
