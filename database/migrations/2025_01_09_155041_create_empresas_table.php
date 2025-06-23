<?php

use GuzzleHttp\Promise\Create;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nit')->unique();
            $table->string('direccion')->nullable();
            $table->string('ciudad')->nullable();
            $table->string('provincia')->nullable();
            $table->string('telefono')->nullable();
            $table->string('celular')->nullable();
            $table->string('correo_electronico')->nullable();
            $table->enum('periodo', ['Mineria', 'Comercial', 'Agropecuaria', 'Industrial'])->default('Mineria');
            $table->integer('gestion')->default(date('Y'));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('empresas');
    }
};
