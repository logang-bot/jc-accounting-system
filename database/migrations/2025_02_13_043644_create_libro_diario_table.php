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
        Schema::create('libro_diario', function (Blueprint $table) {
            $table->id('id_registro');
            $table->date('fecha');
            $table->decimal('monto_debito', 15, 2);
            $table->decimal('monto_credito', 15, 2);
            $table->string('descripcion', 255)->nullable();
            $table->timestamps(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('libro_diario');
    }
};
