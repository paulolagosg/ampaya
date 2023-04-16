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
        if (Schema::hasTable('atenciones')) {
            Schema::table('atenciones', function (Blueprint $table) {
                $table->longText('aten_tlabo_otros')->nullable();
                $table->longText('aten_tima_otros')->nullable();
                $table->longText('aten_tnuclear_otros')->nullable();
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
        if (Schema::hasTable('atenciones')) {
            Schema::table('atenciones', function (Blueprint $table) {
                $table->dropColumn('aten_tlabo_otros');
                $table->dropColumn('aten_tima_otros');
                $table->dropColumn('aten_tnuclear_otros');
            });
        }
    }
};
