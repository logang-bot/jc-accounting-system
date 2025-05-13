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
        Schema::create('activos_fijos', function (Blueprint $table) {
            $table->id('id_activo');
            $table->string('codigo_activo', 50);
            $table->string('nombre_activo', 255);
            $table->text('descripcion')->nullable();
            $table->date('fecha_adquisicion');
            $table->decimal('valor_inicial', 15, 2);
            $table->timestamps(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activos_fijos');
    }
};
