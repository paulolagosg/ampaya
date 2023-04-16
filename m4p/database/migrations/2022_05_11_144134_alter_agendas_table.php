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
        if (Schema::hasTable('agendas')) {
            Schema::table('agendas', function (Blueprint $table) {
                $table->integer('tipa_ncod')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('agendas')) {
            Schema::table('agendas', function (Blueprint $table) {
                $table->dropColumn('tipa_ncod');
            });
        }
    }
};
