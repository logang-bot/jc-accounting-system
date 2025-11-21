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
        Schema::create('cuentas', function (Blueprint $table) {
            $table->id('id_cuenta'); // Clave primaria
            $table->string('codigo_cuenta', 10)->unique();
            $table->string('nombre_cuenta', 150);
            $table->enum('tipo_cuenta', ['Activo', 'Pasivo', 'Patrimonio', 'Ingreso', 'Egreso']);
            $table->integer('nivel')->default(1);

            // Relación jerárquica
            $table->unsignedBigInteger('parent_id')->nullable()->index();
            $table->foreign('parent_id')->references('id_cuenta')->on('cuentas')->onDelete('cascade');

            // Indica si es una cuenta de movimiento
            $table->boolean('es_movimiento')->default(false);
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuentas');
    }
};
