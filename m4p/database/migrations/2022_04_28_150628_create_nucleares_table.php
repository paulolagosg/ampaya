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
        Schema::create('nucleares', function (Blueprint $table) {
            $table->increments('nucl_ncod');
            $table->string('nucl_tnombre');
            $table->string('nucl_tcodigo');
            $table->integer('nucl_nestado');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nucleares');
    }
};
