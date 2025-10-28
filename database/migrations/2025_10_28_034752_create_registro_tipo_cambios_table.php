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
        Schema::create('registro_tipo_cambios', function (Blueprint $table) {
            $table->id();

            // Fecha del registro (día de la tasa)
            $table->date('fecha')->unique();

            // Valores del tipo de cambio con respecto al BOB
            $table->decimal('valor_ufv', 12, 2);
            $table->decimal('valor_sus', 12, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registro_tipo_cambios');
    }
};
