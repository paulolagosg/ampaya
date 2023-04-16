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
        if (Schema::hasTable('personas')) {
            Schema::table('personas', function (Blueprint $table) {
                $table->integer('pers_ntipo_docto');
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
        if (Schema::hasTable('personas')) {
            Schema::table('personas', function (Blueprint $table) {
                $table->dropColumn('pers_ntipo_docto');
            });
        }
    }
};
