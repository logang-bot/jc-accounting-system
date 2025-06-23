<?php

namespace Database\Factories;

use App\Models\CuentasContables;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */

class CuentasContablesFactory extends Factory
{
    protected static function newFactory() {
        return CuentasContablesFactory::new();
    }
/**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\CuentasContables>
     */

     protected $model = CuentasContables::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        // Simulate a level (1–5)
        $nivel = $this->faker->numberBetween(1, 5);

        return [
            'codigo_cuenta' => null, // auto-generated
            'nombre_cuenta' => $this->faker->words(3, true),
            'tipo_cuenta' => $this->faker->randomElement(['Activo', 'Pasivo', 'Patrimonio', 'Ingresos', 'Egresos']),
            'nivel' => $nivel,
            'parent_id' => null,
            'es_movimiento' => $nivel >= 4 ? $this->faker->boolean(70) : false,
            'moneda_principal' => in_array($nivel, [4, 5]) ? $this->faker->randomElement(['BOB', 'USD']) : null,
        ];
    }

    public function withParent(CuentasContables $parent): static
    {
        return $this->state(fn () => [
            'parent_id' => $parent->id_cuenta,
            'tipo_cuenta' => $parent->tipo_cuenta,
        ]);
    }

    public function ofType(string $tipo): static
    {
        return $this->state(fn () => [
            'tipo_cuenta' => $tipo,
        ]);
    }

    public function asMovimiento(): static
    {
        return $this->state(fn () => [
            'es_movimiento' => true,
        ]);
    }

    public function activoPrincipal()
    {
        return $this->state([
            'codigo_cuenta' => '1000000000',
            'nombre_cuenta' => 'ACTIVO',
            'tipo_cuenta' => 'Activo',
            'nivel' => 1,
            'parent_id' => null,
        ]);
    }

    public function pasivoPrincipal()
    {
        return $this->state([
            'codigo_cuenta' => '2000000000',
            'nombre_cuenta' => 'PASIVO',
            'tipo_cuenta' => 'Pasivo',
            'nivel' => 1,
            'parent_id' => null,
        ]);
    }

    public function patrimonioPrincipal()
    {
        return $this->state([
            'codigo_cuenta' => '3000000000',
            'nombre_cuenta' => 'PATRIMONIO',
            'tipo_cuenta' => 'Patrimonio',
            'nivel' => 1,
            'parent_id' => null,
        ]);
    }

    public function ingresosPrincipal()
    {
        return $this->state([
            'codigo_cuenta' => '4000000000',
            'nombre_cuenta' => 'INGRESOS',
            'tipo_cuenta' => 'Ingresos',
            'nivel' => 1,
            'parent_id' => null,
        ]);
    }

    public function egresosPrincipal()
    {
        return $this->state([
            'codigo_cuenta' => '5000000000',
            'nombre_cuenta' => 'EGRESOS',
            'tipo_cuenta' => 'Egresos',
            'nivel' => 1,
            'parent_id' => null,
        ]);
    }
}
