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
            $table->string('mcv_hematology', 10)->default(null)->nullable();
            $table->string('mch_hematology', 10)->default(null)->nullable();
            $table->string('mchc_hematology', 10)->default(null)->nullable();

            $table->string('erythrocyte_arte_hematology', 10)->default(null)->nullable();

            $table->string('neutrophils_hematology', 10)->default(null)->nullable();
            $table->string('lymphocytes_hematology', 10)->default(null)->nullable();
            $table->string('monocytes_hematology', 10)->default(null)->nullable();
            $table->string('eosinophils_hematology', 10)->default(null)->nullable();
            $table->string('basophils_hematology', 10)->default(null)->nullable();

            $table->string('platelet_count_hematology', 10)->default(null)->nullable();

            $table->string('clotting_time_hematology', 10)->default(null)->nullable();
            $table->string('bleeding_time_hematology', 10)->default(null)->nullable();

            $table->string('reticulocyte_count_hematology', 10)->default(null)->nullable();

            $table->string('pt_protime_hematology', 10)->default(null)->nullable();
            $table->string('control_protime_hematology', 10)->default(null)->nullable();
            $table->string('activity_protime_hematology', 10)->default(null)->nullable();

            $table->string('ptt_aptt_hematology', 10)->default(null)->nullable();
            $table->string('control_aptt_hematology', 10)->default(null)->nullable();
            $table->string('ratio_aptt_hematology', 10)->default(null)->nullable();

            $table->string('inr_hematology', 10)->default(null)->nullable();
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
            $table->dropColumn('mcv_hematology');
            $table->dropColumn('mch_hematology');
            $table->dropColumn('mchc_hematology');

            $table->dropColumn('erythrocyte_arte_hematology');

            $table->dropColumn('neutrophils_hematology');
            $table->dropColumn('lymphocytes_hematology');
            $table->dropColumn('monocytes_hematology');
            $table->dropColumn('eosinophils_hematology');
            $table->dropColumn('basophils_hematology');

            $table->dropColumn('platelet_count_hematology');

            $table->dropColumn('clotting_time_hematology');
            $table->dropColumn('bleeding_time_hematology');

            $table->dropColumn('reticulocyte_count_hematology');

            $table->dropColumn('pt_protime_hematology');
            $table->dropColumn('control_protime_hematology');
            $table->dropColumn('activity_protime_hematology');

            $table->dropColumn('ptt_aptt_hematology');
            $table->dropColumn('control_aptt_hematology');
            $table->dropColumn('ratio_aptt_hematology');

            $table->dropColumn('inr_hematology');
        });
    }
};
