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
        Schema::create('personas', function (Blueprint $table) {
            $table->increments('pers_ncod');
            $table->integer('pers_nrut');
            $table->text('pers_tdv',1);
            $table->text('pers_tnombres');
            $table->text('pers_tpaterno');
            $table->text('pers_tmaterno');
            $table->longText('pers_tinfo');
            $table->integer('pers_nfono_fijo');
            $table->integer('pers_nfono_movil');
            $table->date('pers_fnacimiento');
            $table->text('pers_tcorreo');
            $table->text('pers_tdireccion');
            $table->integer('pers_bpaciente');
            $table->text('pers_tfirma');
            $table->integer('prev_ncod');
            $table->integer('espe_ncod');
            $table->integer('gene_ncod');
            $table->integer('ceme_ncod');
            $table->integer('pers_nestado');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personas');
    }
};
