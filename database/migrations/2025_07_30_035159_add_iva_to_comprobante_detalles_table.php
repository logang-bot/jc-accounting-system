<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('comprobante_detalles', function (Blueprint $table) {
            $table->decimal('iva', 10, 2)->nullable()->after('descripcion'); // o el campo que tenga al final
        });
        
        DB::statement("ALTER TABLE comprobante_detalles ADD CONSTRAINT chk_iva_percent CHECK (iva >= 0 AND iva <= 100)");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comprobante_detalles', function (Blueprint $table) {
            //
        });
    }
};
