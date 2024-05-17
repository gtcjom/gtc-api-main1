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

            $table->string('imaging_image', 10)->default(null)->nullable();
            $table->string('breast_single', 10)->default(null)->nullable();
            $table->string('chest_thorax', 10)->default(null)->nullable();
            $table->string('inguinal', 10)->default(null)->nullable();
            $table->string('neck_usd', 10)->default(null)->nullable();
            $table->string('scrotum_testes', 10)->default(null)->nullable();
            $table->string('superficial_soft_tissue', 10)->default(null)->nullable();
            $table->string('thyroid_usd', 10)->default(null)->nullable();
            $table->string('whole_abdomen', 10)->default(null)->nullable();
            $table->string('wab_appendix', 10)->default(null)->nullable();
            $table->string('breast_elasto', 10)->default(null)->nullable();
            $table->string('breast_both', 10)->default(null)->nullable();
            $table->string('lower_abdomen', 10)->default(null)->nullable();
            $table->string('upper_abdomen', 10)->default(null)->nullable();
            $table->string('kub_pelvis', 10)->default(null)->nullable();
            $table->string('kub_prostate', 10)->default(null)->nullable();
            $table->string('guided_aspiration', 10)->default(null)->nullable();
            $table->string('guided_biopsy', 10)->default(null)->nullable();
            $table->string('tvs', 10)->default(null)->nullable();
            $table->string('inguinoscrotal', 10)->default(null)->nullable();
            $table->string('bps', 10)->default(null)->nullable();
            $table->string('hbt', 10)->default(null)->nullable();
            $table->string('kub_only', 10)->default(null)->nullable();
            $table->string('pregnant_ultrasound', 10)->default(null)->nullable();
            $table->string('pelvis_ultrasound', 10)->default(null)->nullable();
            $table->string('neck_ultrasound', 10)->default(null)->nullable();
            $table->string('appendix_ultrasound', 10)->default(null)->nullable();
            $table->string('avdleb_ultrasound', 10)->default(null)->nullable();
            $table->string('avdles_ultrasound', 10)->default(null)->nullable();
            $table->string('vdles_ultrasound', 10)->default(null)->nullable();
            $table->string('adles_ultrasound', 10)->default(null)->nullable();
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
            $table->dropColumn('imaging_image');
            $table->dropColumn('breast_single');
            $table->dropColumn('chest_thorax');
            $table->dropColumn('inguinal');
            $table->dropColumn('neck_usd');
            $table->dropColumn('scrotum_testes');
            $table->dropColumn('superficial_soft_tissue');
            $table->dropColumn('thyroid_usd');
            $table->dropColumn('whole_abdomen');
            $table->dropColumn('wab_appendix');
            $table->dropColumn('breast_elasto');
            $table->dropColumn('breast_both');
            $table->dropColumn('lower_abdomen');
            $table->dropColumn('upper_abdomen');
            $table->dropColumn('kub_pelvis');
            $table->dropColumn('kub_prostate');
            $table->dropColumn('guided_aspiration');
            $table->dropColumn('guided_biopsy');
            $table->dropColumn('tvs');
            $table->dropColumn('inguinoscrotal');
            $table->dropColumn('bps');
            $table->dropColumn('hbt');
            $table->dropColumn('kub_only');
            $table->dropColumn('pregnant_ultrasound');
            $table->dropColumn('pelvis_ultrasound');
            $table->dropColumn('neck_ultrasound');
            $table->dropColumn('appendix_ultrasound');
            $table->dropColumn('avdleb_ultrasound');
            $table->dropColumn('avdles_ultrasound');
            $table->dropColumn('vdles_ultrasound');
            $table->dropColumn('adles_ultrasound');
        });
    }
};
