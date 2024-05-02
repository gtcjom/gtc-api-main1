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
            $table->string('hbsag', 10)->default(null)->nullable();
            $table->string('anti_hbs', 10)->default(null)->nullable();

            $table->string('anti_hcv', 10)->default(null)->nullable();
            $table->string('syphilis', 10)->default(null)->nullable();
            $table->string('aso', 10)->default(null)->nullable();
            $table->string('ra_rf', 10)->default(null)->nullable();

            $table->string('crp', 10)->default(null)->nullable();
            $table->string('troponin', 10)->default(null)->nullable();
            $table->string('ck_mb', 10)->default(null)->nullable();
            $table->string('salmonella_typhi_h', 10)->default(null)->nullable();
            $table->string('salmonella_typhi_ah', 10)->default(null)->nullable();
            $table->string('salmonella_typhi_bh', 10)->default(null)->nullable();
            $table->string('salmonella_typhi_ch', 10)->default(null)->nullable();
            $table->string('salmonella_paratyphi_o', 10)->default(null)->nullable();
            $table->string('salmonella_paratyphi_ao', 10)->default(null)->nullable();
            $table->string('salmonella_paratyphi_bo', 10)->default(null)->nullable();
            $table->string('salmonella_typhi_co', 10)->default(null)->nullable();

            $table->string('ns_dengue', 10)->default(null)->nullable();
            $table->string('igg_dengue', 10)->default(null)->nullable();
            $table->string('igm_dengue', 10)->default(null)->nullable();

            $table->string('igg_typhoid', 10)->default(null)->nullable();
            $table->string('igm_typhoid', 10)->default(null)->nullable();
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
            $table->dropColumn('color_urine');
            $table->dropColumn('transparency_urine');

            $table->dropColumn('reaction_urine');
            $table->dropColumn('gravity_urine');
            $table->dropColumn('glucose_urine');
            $table->dropColumn('protein_urine');

            $table->dropColumn('wbc_urine');
            $table->dropColumn('rbc_urine');
            $table->dropColumn('epithelial_cells_urine');
            $table->dropColumn('bacteria_urine');
            $table->dropColumn('mucus_thread_urine');

            $table->dropColumn('amorphous_urates_urine');
            $table->dropColumn('amorphous_phosphates_urine');
            $table->dropColumn('calciun_oxalates_urine');
            $table->dropColumn('triple_phosphates_urine');
            $table->dropColumn('uric_acid_urine');
            $table->dropColumn('others_urine');

            $table->dropColumn('hyaline_cast_urine');
            $table->dropColumn('wbc_cast_urine');
            $table->dropColumn('rbc_cast_urine');
            $table->dropColumn('granular_cast_urine');
        });
    }
};
