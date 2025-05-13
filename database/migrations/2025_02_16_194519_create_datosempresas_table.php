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
        Schema::create('datosempresas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id');  // Relación con la tabla empresas
            $table->string('nit', 20);
            $table->string('direccion', 255)->nullable();
            $table->string('ciudad', 100)->nullable();
            $table->string('provincia', 100)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('celular', 20)->nullable();
            $table->string('correo_electronico', 100)->nullable();
            $table->enum('periodo', [
                'Enero - Diciembre (Comercial)',
                'Abril - Marzo (Industrial)',
                'Junio - Julio (Agropecuaria)',
                'Octubre - Septiembre (Minera)'
            ]);
            $table->year('gestion');
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datosempresas');
    }
};
