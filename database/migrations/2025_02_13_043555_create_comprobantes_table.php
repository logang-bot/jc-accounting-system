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
        Schema::create('comprobantes', function (Blueprint $table) {
            $table->id('id_comprobante'); // Clave primaria

            $table->string('numero_comprobante')->unique(); // Número de comprobante (único)
            $table->date('fecha'); // Fecha del comprobante
            $table->string('tipo_comprobante'); // Tipo de comprobante (Ingreso, Egreso, Diario, etc.)
            $table->string('glosa_general', 255)->nullable(); // Glosa general

            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comprobantes');
    }
};
