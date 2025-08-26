<?php

namespace Database\Seeders;

use App\Models\Comprobante;
use App\Models\ComprobanteDetalles;
use App\Models\CuentasContables;
use App\Models\Empresa;
use App\Models\User;
use Illuminate\Database\Seeder;

class ComprobantesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        // Asegúrate de tener al menos un usuario (ajusta si usas auth diferente)
        $user = User::first() ?? User::factory()->create();

        // Obtiene cuentas de movimiento
        $cuentasMovimiento = CuentasContables::where('es_movimiento', true)->get();

        if ($cuentasMovimiento->count() < 2) {
            $this->command->error('Se necesitan al menos dos cuentas de movimiento para crear comprobantes.');
            return;
        }

        // Crear 10 comprobantes
        for ($i = 1; $i <= 10; $i++) {
            $tasaCambio = fake()->randomFloat(4, 6.50, 7.20);
            $fecha = now()->subDays(rand(1, 90))->toDateString();

            $comprobante = Comprobante::create([
                'numero' => 'COMP-' . now()->format('Y') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'fecha' => $fecha,
                'tipo' => fake()->randomElement(['ingreso', 'egreso', 'traspaso']),
                'destinatario' => fake()->name(),
                'lugar' => fake()->city(),
                'descripcion' => fake()->sentence(),
                'total' => 0,
                'user_id' => $user->id,
                'tasa_cambio' => $tasaCambio,
            ]);

            // Seleccionar dos cuentas distintas para debe y haber
            $cuentaDebe = $cuentasMovimiento->random();
            $cuentaHaber = $cuentasMovimiento->where('id_cuenta', '!=', $cuentaDebe->id_cuenta)->random();

            $monto = fake()->randomFloat(2, 100, 500);

            ComprobanteDetalles::create([
                'comprobante_id' => $comprobante->id,
                'cuenta_contable_id' => $cuentaDebe->id_cuenta,
                'descripcion' => fake()->sentence(),
                'debe' => $monto,
                'haber' => 0,
            ]);

            ComprobanteDetalles::create([
                'comprobante_id' => $comprobante->id,
                'cuenta_contable_id' => $cuentaHaber->id_cuenta,
                'descripcion' => fake()->sentence(),
                'debe' => 0,
                'haber' => $monto,
            ]);

            $comprobante->update(['total' => $monto]);
        }
    }


    /**
     * Ejecuta la creación de comprobantes para una empresa.
     */
    public static function seedForEmpresa(Empresa $empresa): void
    {
        // Asegúrate de tener al menos un usuario
        $user = User::first() ?? User::factory()->create();

        // Solo cuentas de movimiento de esta empresa
        $cuentasMovimiento = CuentasContables::where('empresa_id', $empresa->id)
            ->where('es_movimiento', true)
            ->get();

        if ($cuentasMovimiento->count() < 2) {
            info("No hay suficientes cuentas de movimiento para empresa: {$empresa->id}");
            return;
        }

        for ($i = 1; $i <= 10; $i++) {
            $tasaCambio = fake()->randomFloat(4, 6.50, 7.20);
            $fecha = now()->subDays(rand(1, 90))->toDateString();

            $comprobante = Comprobante::create([
                'empresa_id' => $empresa->id, // ✅ relación con empresa
                'numero' => 'COMP-' . now()->format('Y') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'destinatario' => fake()->name(),
                'lugar' => fake()->city(),
                'fecha' => $fecha,
                'tipo' => fake()->randomElement(['ingreso', 'egreso', 'traspaso']),
                'descripcion' => fake()->sentence(),
                'total' => 0,
                'user_id' => $user->id,
                'tasa_cambio' => $tasaCambio,
            ]);

            // Seleccionar dos cuentas distintas
            $cuentaDebe = $cuentasMovimiento->random();
            $cuentaHaber = $cuentasMovimiento->where('id_cuenta', '!=', $cuentaDebe->id_cuenta)->random();

            $monto = fake()->randomFloat(2, 100, 500);

            ComprobanteDetalles::create([
                'comprobante_id' => $comprobante->id,
                'cuenta_contable_id' => $cuentaDebe->id_cuenta,
                'descripcion' => fake()->sentence(),
                'debe' => $monto,
                'haber' => 0,
            ]);

            ComprobanteDetalles::create([
                'comprobante_id' => $comprobante->id,
                'cuenta_contable_id' => $cuentaHaber->id_cuenta,
                'descripcion' => fake()->sentence(),
                'debe' => 0,
                'haber' => $monto,
            ]);

            $comprobante->update(['total' => $monto]);
        }
    }

}
