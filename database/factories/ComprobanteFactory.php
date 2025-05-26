<?php

namespace Database\Factories;

use App\Models\Comprobante;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comprobante>
 */
class ComprobanteFactory extends Factory
{
    protected $model = Comprobante::class;

    public function definition()
    {
        $tipos = ['ingreso', 'egreso', 'traspaso', 'ajuste'];

        return [
            'numero' => 'COMP-' . now()->format('Y') . '-' . $this->faker->unique()->numerify('####'),
            'fecha' => $this->faker->dateTimeBetween('-1 years', 'now')->format('Y-m-d'),
            'tipo' => $this->faker->randomElement($tipos),
            'descripcion' => $this->faker->sentence(),
            'total' => 0, // We'll update total after creating details
            'user_id' => User::factory(), // assumes you have a User factory
        ];
    }
}
