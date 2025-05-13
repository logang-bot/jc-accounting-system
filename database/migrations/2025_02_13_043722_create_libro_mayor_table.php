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
        Schema::create('libro_mayor', function (Blueprint $table) {
            $table->unsignedBigInteger('id_cuenta')->primary();
            $table->decimal('saldo_anterior', 15, 2)->default(0.00);
            $table->decimal('saldo_actual', 15, 2)->default(0.00);
            $table->foreign('id_cuenta')->references('id_cuenta')->on('cuentas');
            $table->timestamps(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('libro_mayor');
    }
};
