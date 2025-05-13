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
        Schema::create('entradas_inventario', function (Blueprint $table) {
            $table->id('id_entrada');
            $table->unsignedBigInteger('id_producto');
            $table->integer('cantidad');
            $table->date('fecha_entrada');
            $table->enum('tipo_entrada', ['Compra', 'Devolución', 'Producción']);
            $table->string('descripcion', 255)->nullable();
            $table->foreign('id_producto')->references('id_producto')->on('productos');
            $table->timestamps(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entradas_inventario');
    }
};
