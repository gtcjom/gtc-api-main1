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
            //OGTT Oral Glucose Tolerance Test
            //Glucose Load
            $table->string('glucose_load', 10)->default(null)->nullable();
            //Blood Glucose
            $table->string('blood_fbs', 10)->default(null)->nullable();
            $table->string('blood_first_hour', 10)->default(null)->nullable();
            $table->string('blood_second_hour', 10)->default(null)->nullable();
            $table->string('blood_third_hour', 10)->default(null)->nullable();
            //Urine Glucose
            $table->string('urine_fasting', 10)->default(null)->nullable();
            $table->string('urine_first_hour', 10)->default(null)->nullable();
            $table->string('urine_second_hour', 10)->default(null)->nullable();
            $table->string('urine_third_hour', 10)->default(null)->nullable();
            //50 Grams Oral Glucose Challenge
            $table->string('gogct_result', 10)->default(null)->nullable();

            $table->string('ogtt_remark', 10)->default(null)->nullable();
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
            //culture and sensitivity Initial Result
            $table->dropColumn('glucose_load');
            $table->dropColumn('blood_fbs');
            $table->dropColumn('blood_first_hour');
            $table->dropColumn('blood_second_hour');
            $table->dropColumn('blood_third_hour');
            $table->dropColumn('urine_fasting');
            $table->dropColumn('urine_first_hour');
            $table->dropColumn('urine_second_hour');
            $table->dropColumn('urine_third_hour');
            $table->dropColumn('gogct_result');
            $table->dropColumn('ogtt_remark');
        });
    }
};
