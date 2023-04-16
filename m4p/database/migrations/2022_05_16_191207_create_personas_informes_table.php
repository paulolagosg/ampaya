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
        Schema::create('personas_informes', function (Blueprint $table) {
            $table->increments('pein_ncod');
            $table->integer('pers_nrut');
            $table->text('pein_tnombre');
            $table->longText('pein_tinforme');
            $table->date('pein_finforme');
            $table->integer('tiin_ncod');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personas_informes');
    }
};
