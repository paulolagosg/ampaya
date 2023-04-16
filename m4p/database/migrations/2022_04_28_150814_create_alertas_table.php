<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alertas', function (Blueprint $table) {
            $table->increments('aler_ncod');
            $table->string('aler_ttitulo');
            $table->longText('aler_tcontenido');
            $table->integer('aler_nleida');
            $table->integer('aler_nestado');
            $table->integer('pers_nrut_origen');
            $table->integer('pers_nrut_destino');
            $table->integer('tial_ncod');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alertas');
    }
};
