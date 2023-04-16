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
        Schema::create('personas_documentos', function (Blueprint $table) {
            $table->increments('pedo_ncod');
            $table->integer('pers_nrut');
            $table->text('pedo_tdocumento');
            $table->date('pedo_fdocumento');
            $table->integer('tido_ncod');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personas_documentos');
    }
};
