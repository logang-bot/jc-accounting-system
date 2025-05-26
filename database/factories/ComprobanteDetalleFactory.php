<?php

namespace Database\Factories;

use App\Models\Comprobante;
use App\Models\ComprobanteDetalles;
use App\Models\CuentasContables;
use App\Models\DetalleComprobante;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ComprobanteDetalle>
 */
class ComprobanteDetalleFactory extends Factory
{
    protected $model = ComprobanteDetalles::class;

    public function definition()
    {
        // Randomly assign debit or credit but not both > 0 at the same time
        $debe = $this->faker->randomFloat(2, 0, 1000);
        $haber = $this->faker->boolean() ? 0 : $debe;
        if ($haber > 0) {
            $debe = 0;
        }

        return [
            'comprobante_id' => Comprobante::factory(),
            'cuenta_contable_id' => CuentasContables::inRandomOrder()->first()?->id_cuenta ?? 1, // fallback to 1 if none exist
            'debe' => $debe,
            'haber' => $haber,
            'descripcion' => $this->faker->sentence(),
        ];
    }
}
