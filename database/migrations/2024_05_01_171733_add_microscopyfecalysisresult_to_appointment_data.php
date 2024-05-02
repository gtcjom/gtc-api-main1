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
            $table->string('color_fecalysis', 10)->default(null)->nullable();
            $table->string('consistency_fecalysis', 10)->default(null)->nullable();

            $table->string('rbc_fecalysis', 10)->default(null)->nullable();
            $table->string('wbc_fecalysis', 10)->default(null)->nullable();
            $table->string('fat_globules_fecalysis', 10)->default(null)->nullable();
            $table->string('yeast_cells_fecalysis', 10)->default(null)->nullable();

            $table->string('fecal_occult_blood_fecalysis', 10)->default(null)->nullable();

            $table->string('ascaris_lumbricoides', 10)->default(null)->nullable();
            $table->string('triciuris_trichiura', 10)->default(null)->nullable();
            $table->string('hookworm', 10)->default(null)->nullable();
            $table->string('entamoeba_histolytica_cyst', 10)->default(null)->nullable();
            $table->string('entamoeba_histolytica_trophozoite', 10)->default(null)->nullable();
            $table->string('entamoeba_coli_cyst', 10)->default(null)->nullable();
            $table->string('entamoeba_coli_trophozoite', 10)->default(null)->nullable();
            $table->string('giardia_lamblia_cyst', 10)->default(null)->nullable();
            $table->string('giardia_lamblia_trophozoite', 10)->default(null)->nullable();

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

            $table->dropColumn('color_fecalysis');
            $table->dropColumn('consistency_fecalysis');
            $table->dropColumn('rbc_fecalysis');
            $table->dropColumn('wbc_fecalysis');
            $table->dropColumn('fat_globules_fecalysis');
            $table->dropColumn('yeast_cells_fecalysis');
            $table->dropColumn('fecal_occult_blood_fecalysis');
            $table->dropColumn('ascaris_lumbricoides');
            $table->dropColumn('triciuris_trichiura');
            $table->dropColumn('hookworm');
            $table->dropColumn('entamoeba_histolytica_cyst');
            $table->dropColumn('entamoeba_histolytica_trophozoite');
            $table->dropColumn('entamoeba_coli_cyst');
            $table->dropColumn('entamoeba_coli_trophozoite');
            $table->dropColumn('giardia_lamblia_cyst');
            $table->dropColumn('giardia_lamblia_trophozoite');
        });
    }
};
