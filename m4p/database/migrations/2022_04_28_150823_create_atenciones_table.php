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
        Schema::create('atenciones', function (Blueprint $table) {
            $table->increments('aten_ncod');
            $table->integer('pers_nrut_paciente');
            $table->integer('pers_nrut_medico');
            $table->date('aten_ffecha');
            $table->longText('aten_tsintomas');
            $table->text('aten_tdiagnostico');
            $table->text('aten_tindicaciones');
            $table->text('aten_timagenes');
            $table->text('aten_tlaboratorio');
            $table->text('aten_tnuclear');
            $table->text('aten_tfarmacos');
            $table->integer('esat_ncod');
            $table->integer('agen_ncod');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('atenciones');
    }
};
