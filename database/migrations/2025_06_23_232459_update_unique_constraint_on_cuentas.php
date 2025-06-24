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
        Schema::table('cuentas', function (Blueprint $table) {
            $table->dropUnique(['codigo_cuenta']); // Quita el unique simple

            // Agrega unique combinado empresa_id + codigo_cuenta
            $table->unique(['empresa_id', 'codigo_cuenta']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
