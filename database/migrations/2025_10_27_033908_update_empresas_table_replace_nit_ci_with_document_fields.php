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
        Schema::table('empresas', function (Blueprint $table) {
            // Remove old column
            $table->dropColumn('nit_ci');

            // Add new columns
            $table->string('documento')->unique();
            $table->enum('tipo_documento', ['CI', 'NIT'])->default('CI');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            // Reverse the changes
            $table->dropColumn(['documento', 'tipo_documento']);
            $table->string('nit_ci')->nullable();
        });
    }
};
