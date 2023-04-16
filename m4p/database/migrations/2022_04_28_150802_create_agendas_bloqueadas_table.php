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
        Schema::create('agendas_bloqueadas', function (Blueprint $table) {
            $table->increments('agbl_ncod');
            $table->date('agbl_finicio');
            $table->date('agbl_ftermino');
            $table->integer('pers_nrut_medico');
            $table->integer('ceme_ncod');
            $table->integer('agbl_nestado');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agendas_bloqueadas');
    }
};
