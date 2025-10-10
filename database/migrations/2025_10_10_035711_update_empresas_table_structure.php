<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            // Drop old columns (only if they exist)
            $table->dropColumn([
                'nit',
                'direccion',
                'ciudad',
                'provincia',
                'telefono',
                'celular',
                'correo_electronico',
                'gestion',
                'periodo'
            ]);
        });

        Schema::table('empresas', function (Blueprint $table) {
            // Add / modify new columns
            $table->string('nit_ci')->nullable()->after('name'); // replaces nit or ci
            $table->boolean('casa_matriz')->default(false)->after('nit_ci');
            $table->date('fecha_inicio')->nullable()->after('casa_matriz');
            $table->date('fecha_fin')->nullable()->after('fecha_inicio');
            $table->string('periodo')->nullable()->after('fecha_fin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn(['nit_ci', 'casa_matriz', 'fecha_inicio', 'fecha_fin', 'periodo']);

            // Restore previous columns if you rollback
            $table->string('nit')->nullable();
            $table->string('direccion')->nullable();
            $table->string('ciudad')->nullable();
            $table->string('provincia')->nullable();
            $table->string('telefono')->nullable();
            $table->string('celular')->nullable();
            $table->string('correo_electronico')->nullable();
            $table->string('gestion')->nullable();
            $table->boolean('activa')->default(true);
        });
    }
};
