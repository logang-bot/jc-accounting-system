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
        Schema::create('salidas_inventario', function (Blueprint $table) {
            $table->id('id_salida');
            $table->unsignedBigInteger('id_producto');
            $table->integer('cantidad');
            $table->date('fecha_salida');
            $table->enum('tipo_salida', ['Venta', 'Consumo', 'Devolución']);
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
        Schema::dropIfExists('salidas_inventario');
    }
};
