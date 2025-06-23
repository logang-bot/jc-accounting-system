<?php

namespace Database\Seeders;

use App\Models\Empresa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $periodos = ['Mineria', 'Comercial', 'Agropecuaria', 'Industrial'];

        // Instanciar los seeders dependientes
        $cuentasSeeder = new CuentasSeeder();
        $comprobantesSeeder = new ComprobantesSeeder();

        for ($i = 1; $i <= 5; $i++) {
            $empresa = Empresa::create([
                'name' => 'Empresa ' . $i,
                'nit' => fake()->numerify('######'),
                'direccion' => fake()->address(),
                'ciudad' => fake()->city(),
                'provincia' => fake()->state(),
                'telefono' => fake()->phoneNumber(),
                'celular' => fake()->phoneNumber(),
                'correo_electronico' => fake()->companyEmail(),
                'periodo' => fake()->randomElement($periodos),
                'gestion' => now()->year,
            ]);

            // ✅ Poblar Cuentas y Comprobantes para esta empresa
            $cuentasSeeder->seedForEmpresa($empresa);
            $comprobantesSeeder->seedForEmpresa($empresa);
        }
    }
}
