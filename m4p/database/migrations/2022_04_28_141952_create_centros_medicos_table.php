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
        Schema::create('centros_medicos', function (Blueprint $table) {
            $table->increments('ceme_ncod');
            $table->string('ceme_tnombre');
            $table->string('ceme_tdescripcion');
            $table->string('ceme_tdireccion');
            $table->string('ceme_tcorreo');
            $table->bigInteger('ceme_nfono_fijo');
            $table->bigInteger('ceme_nfono_movil');
            $table->string('ceme_tlogo');
            $table->integer('ceme_nestado');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('centros_medicos');
    }
};
