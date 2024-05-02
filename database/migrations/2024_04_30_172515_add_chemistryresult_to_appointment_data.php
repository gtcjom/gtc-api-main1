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


            //FBS
            $table->string('fbs', 10)->default(null)->nullable();
            //RBS
            $table->string('rbs', 10)->default(null)->nullable();
            //Creatinine
            $table->string('creatinine', 10)->default(null)->nullable();
            //Uric Acid
            $table->string('uric_acid', 10)->default(null)->nullable();
            //SGOT
            $table->string('sgot', 10)->default(null)->nullable();
            //SGPT
            $table->string('sgpt', 10)->default(null)->nullable();
            //Alkaline Phos
            $table->string('alkaline_phos', 10)->default(null)->nullable();
            //LDH
            $table->string('ldh', 10)->default(null)->nullable();
            //GGT
            $table->string('ggt', 10)->default(null)->nullable();
            //Magnesium
            $table->string('magnesium', 10)->default(null)->nullable();
            //Phophorus
            $table->string('phophorus', 10)->default(null)->nullable();
            //Amylase
            $table->string('amylase', 10)->default(null)->nullable();

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
            $table->dropColumn('fbs');
            //culture and sensitivity Initial Result
            $table->dropColumn('rbs');
            //culture and sensitivity Initial Result
            $table->dropColumn('creatinine');
            //culture and sensitivity Initial Result
            $table->dropColumn('uric_acid');
            //culture and sensitivity Initial Result
            $table->dropColumn('sgot');
            //culture and sensitivity Initial Result
            $table->dropColumn('sgpt');
            //culture and sensitivity Initial Result
            $table->dropColumn('alkaline_phos');
            //culture and sensitivity Initial Result
            $table->dropColumn('ldh');
            //culture and sensitivity Initial Result
            $table->dropColumn('ggt');
            //culture and sensitivity Initial Result
            $table->dropColumn('magnesium');
            //culture and sensitivity Initial Result
            $table->dropColumn('phophorus');
            //culture and sensitivity Initial Result
            $table->dropColumn('amylase');

        });
    }
};
