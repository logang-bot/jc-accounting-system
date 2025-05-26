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

            $table->string('descripcion')->nullable();
            $table->decimal('debe', 15, 2)->default(0);
            $table->decimal('haber', 15, 2)->default(0);
            $table->timestamps();

            // Definición de la foreign key manualmente
            $table->foreign('cuenta_contable_id')
                ->references('id_cuenta')
                ->on('cuentas')
                ->onDelete('restrict');
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
