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
        Schema::create('agendas', function (Blueprint $table) {
            $table->increments('agen_ncod');
            $table->date('agen_finicio');
            $table->date('agen_ftermino');
            $table->integer('agen_nsobrecupo');
            $table->integer('pers_nrut_paciente');
            $table->integer('pers_nrut_medico');
            $table->integer('tiat_ncod');            
            $table->integer('ceme_ncod');
            $table->integer('esag_ncod');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agendas');
    }
};
