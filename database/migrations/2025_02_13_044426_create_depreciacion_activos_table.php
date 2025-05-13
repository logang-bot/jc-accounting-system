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
        Schema::create('depreciacion_activos', function (Blueprint $table) {
            $table->id('id_depreciacion');
            $table->unsignedBigInteger('id_activo');
            $table->date('periodo');
            $table->decimal('valor_depreciacion', 15, 2);
            $table->decimal('valor_contable', 15, 2);
            $table->foreign('id_activo')->references('id_activo')->on('activos_fijos')->onDelete('cascade');
            $table->timestamps(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('depreciacion_activos');
    }
};
