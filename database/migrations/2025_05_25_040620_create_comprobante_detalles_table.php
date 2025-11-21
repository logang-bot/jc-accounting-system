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
        Schema::create('comprobante_detalles', function (Blueprint $table) {
            $table->id(); // Primary key

            // Clave foránea a comprobantes
            $table->foreignId('comprobante_id')
                ->constrained('comprobantes')
                ->onDelete('cascade');

            // Clave foránea a cuentas contables
            $table->unsignedBigInteger('cuenta_contable_id');
            $table->foreign('cuenta_contable_id')
                ->references('id_cuenta')
                ->on('cuentas')
                ->onDelete('restrict');

            $table->string('descripcion', 300)->nullable();

            // Montos en Bolivianos
            $table->decimal('debe_bs', 15, 2)->default(0);
            $table->decimal('haber_bs', 15, 2)->default(0);

            // Montos en Dólares
            $table->decimal('debe_usd', 15, 2)->default(0);
            $table->decimal('haber_usd', 15, 2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comprobante_detalles');
    }
};
