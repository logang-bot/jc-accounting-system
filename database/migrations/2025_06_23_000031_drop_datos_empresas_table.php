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
        Schema::dropIfExists('datosempresas');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Opcional: puedes recrearla si quieres revertir
        Schema::create('datos_empresas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }
};
