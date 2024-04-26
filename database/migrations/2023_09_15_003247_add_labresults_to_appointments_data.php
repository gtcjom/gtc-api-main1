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
        Schema::table('appointment_data', function (Blueprint $table) {
            //
            $table->string('hemoglobin')->default(null)->nullable();
            $table->string('hematocrit')->default(null)->nullable();
            $table->string('rcbc')->default(null)->nullable();
            $table->string('wbc')->default(null)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appointment_data', function (Blueprint $table) {
            //
            $table->dropColumn('hemoglobin');
            $table->dropColumn('hematocrit');
            $table->dropColumn('rcbc');
            $table->dropColumn('wbc');
        });
    }
};
