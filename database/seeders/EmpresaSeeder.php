<?php

namespace Database\Seeders;

use App\Models\Empresa;
use Illuminate\Database\Seeder;

class EmpresaSeeder extends Seeder
{
    public function run(): void
    {
        $periodos = ['Mineria', 'Comercial', 'Agropecuaria', 'Industrial'];
        $tiposDocumento = ['NIT', 'CI'];

        for ($i = 1; $i <= 5; $i++) {
            Empresa::create([
                'name' => 'Empresa ' . $i,
                'tipo_documento' => fake()->randomElement($tiposDocumento),
                'numero_documento' => fake()->numerify('##########'),
                'direccion' => fake()->address(),
                'ciudad' => fake()->city(),
                'telefono' => fake()->phoneNumber(),
                'casa_matriz' => fake()->boolean(),
                'fecha_inicio' => fake()->date(),
                'fecha_fin' => fake()->date(),
                'periodo' => fake()->randomElement($periodos),
                'activa' => true,
            ]);
        }
    }
}
