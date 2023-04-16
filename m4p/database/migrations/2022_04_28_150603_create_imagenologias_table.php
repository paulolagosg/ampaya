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
        Schema::create('imagenologias', function (Blueprint $table) {
            $table->increments('iman_ncod');
            $table->string('iman_tnombre');
            $table->string('iman_tcodigo');
            $table->integer('iman_nestado');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('imagenologias');
    }
};
