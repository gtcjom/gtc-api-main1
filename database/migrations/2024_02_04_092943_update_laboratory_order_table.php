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
        Schema::table('laboratory_orders', function (Blueprint $table) {
            $table->foreignId('health_unit_id')->default(null)->nullable();
            $table->string('attachment')->nullable();
            $table->string('lab_result_notes')->nullable();
            $table->string('lab_result_reading')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('laboratory_orders', function (Blueprint $table) {
            //
            $table->dropColumn('health_unit_id');
            $table->dropColumn('attachment');
            $table->dropColumn('lab_result_notes');
            $table->dropColumn('lab_result_reading');
        });
    }
};
