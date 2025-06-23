<?php

namespace Database\Factories;

use App\Models\Empresa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Empresa>
 */
class EmpresaFactory extends Factory
{
    protected $model = Empresa::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'nit' => $this->faker->unique()->numerify('#######'),
            'direccion' => $this->faker->address,
            'ciudad' => $this->faker->city,
            'provincia' => $this->faker->state,
            'telefono' => $this->faker->phoneNumber,
            'celular' => $this->faker->phoneNumber,
            'correo_electronico' => $this->faker->unique()->safeEmail,
            'periodo' => $this->faker->randomElement(['Mineria', 'Comercial', 'Agropecuaria', 'Industrial']),
            'gestion' => now()->year,
        ];
    }
}
