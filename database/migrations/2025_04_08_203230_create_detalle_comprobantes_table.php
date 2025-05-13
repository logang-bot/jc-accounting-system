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
        Schema::create('detalle_comprobantes', function (Blueprint $table) {
            $table->id(); // Clave primaria del detalle

            // Claves foráneas
            $table->unsignedBigInteger('comprobante_id'); // Relación al encabezado
            $table->unsignedBigInteger('cuenta_id'); // Relación a la cuenta usada en el asiento

            // Montos contables
            $table->decimal('debe', 15, 2)->default(0);
            $table->decimal('haber', 15, 2)->default(0);

            $table->text('detalle')->nullable(); // Detalle o comentario individual del asiento

            // Relaciones
            $table->foreign('comprobante_id')
                ->references('id_comprobante')
                ->on('comprobantes')
                ->onDelete('cascade');

            $table->foreign('cuenta_id') // Aquí es donde cambiamos la referencia a la tabla 'cuentas'
                ->references('id_cuenta') // Asegúrate de que el campo sea el correcto en la tabla 'cuentas'
                ->on('cuentas') // Nombre correcto de la tabla
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_comprobantes');
    }
};
