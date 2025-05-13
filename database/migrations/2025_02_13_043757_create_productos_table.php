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
        Schema::create('productos', function (Blueprint $table) {
            $table->id('id_producto');
            $table->string('codigo_producto', 50);
            $table->string('nombre_producto', 255);
            $table->text('descripcion')->nullable();
            $table->string('categoria', 100)->nullable();
            $table->decimal('precio_unitario', 15, 2);
            $table->integer('stock_actual')->default(0);
            $table->timestamps(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    /*public function down(): void
    {
        Schema::table('entradas_inventario', function (Blueprint $table) {
            $table->dropForeign(['id_producto']);
        });
        
        Schema::table('salidas_inventario', function (Blueprint $table) {
            $table->dropForeign(['id_producto']);
        });

        Schema::dropIfExists('productos');
    }*/
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
