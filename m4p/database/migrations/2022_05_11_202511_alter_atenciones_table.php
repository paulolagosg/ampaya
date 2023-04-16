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
                $table->text('aten_totros')->nullable();
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
                $table->dropColumn('aten_totros');
            });
        }
    }
};
