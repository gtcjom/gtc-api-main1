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
            //24 Hours Urine Creatinine Clearance
            $table->string('hour_urine_volume', 10)->default(null)->nullable();
            $table->string('serum_creatinine', 10)->default(null)->nullable();
            $table->string('urine_creatinine', 10)->default(null)->nullable();
            $table->string('hours_urine', 10)->default(null)->nullable();
            $table->string('creatinine_clearance', 10)->default(null)->nullable();
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
            //hours urine creatinine
            $table->dropColumn('hour_urine_volume');
            $table->dropColumn('serum_creatinine');
            $table->dropColumn('urine_creatinine');
            $table->dropColumn('hours_urine');
            $table->dropColumn('creatinine_clearance');
        });
    }
};
