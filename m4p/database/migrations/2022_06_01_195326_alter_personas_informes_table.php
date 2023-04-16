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
        if (Schema::hasTable('personas_informes')) {
            Schema::table('personas_informes', function (Blueprint $table) {
                $table->text('pers_nrut_medico')->nullable();
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
        if (Schema::hasTable('personas_informes')) {
            Schema::table('personas_informes', function (Blueprint $table) {
                $table->dropColumn('pers_nrut_medico');
            });
        }
    }
};
