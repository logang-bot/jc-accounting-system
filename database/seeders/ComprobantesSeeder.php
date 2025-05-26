<?php

namespace Database\Seeders;

use App\Models\Comprobante;
use App\Models\ComprobanteDetalles;
use App\Models\CuentasContables;
use App\Models\DetalleComprobante;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            $comprobante = Comprobante::create([
                'numero' => 'COMP-' . now()->format('Y') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'fecha' => now()->subDays(rand(1, 90))->toDateString(),
                'tipo' => fake()->randomElement(['ingreso', 'egreso', 'traspaso']),
                'descripcion' => fake()->sentence(),
                'total' => 0, // se actualizará luego
                'user_id' => $user->id,
            ]);

            $detalles = [];
            $total = 0;

            // Generar entre 2 y 4 detalles
            $numDetalles = rand(2, 4);
            for ($j = 0; $j < $numDetalles; $j++) {
                $cuenta = $cuentasMovimiento->random();
                $monto = fake()->randomFloat(2, 100, 500);

                $debe = $j % 2 === 0 ? $monto : 0;
                $haber = $j % 2 !== 0 ? $monto : 0;

                $detalles[] = ComprobanteDetalles::create([
                    'comprobante_id' => $comprobante->id,
                    'cuenta_contable_id' => $cuenta->id_cuenta,
                    'descripcion' => fake()->sentence(),
                    'debe' => $debe,
                    'haber' => $haber,
                ]);

                $total += max($debe, $haber);
            }

            // Actualizar total del comprobante
            $comprobante->update(['total' => $total]);
        }
    }
}
