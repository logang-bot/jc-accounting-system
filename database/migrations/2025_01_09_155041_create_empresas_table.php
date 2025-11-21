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
        Schema::create('empresas', function (Blueprint $table) {
            $table->id(); // BIGINT
            $table->string('nombre', 150);

            // Documento (selección obligatoria entre CI o NIT)
            $table->enum('tipo_documento', ['CI', 'NIT']);

            $table->string('numero_documento', 30)->nullable();

            // Datos de ubicación y contacto
            $table->string('direccion', 200)->nullable();
            $table->string('ciudad', 100)->nullable();
            $table->string('telefono', 15)->nullable();

            // Sucursales y matriz
            $table->boolean('casa_matriz')->default(false);
            $table->string('sucursal', 150)->nullable();

            // Tipo de empresa
            $table->string('tipo_empresa', 50)->nullable();

            // Método de inventario (selección entre 2 metodologías)
            $table->enum('metodo', ['PEPS', 'Promedio'])->default('PEPS');

            // Fechas
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();

            // Estado
            $table->boolean('activa')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
