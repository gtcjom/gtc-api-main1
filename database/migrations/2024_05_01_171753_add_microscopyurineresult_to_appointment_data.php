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
            $table->string('color_urine', 10)->default(null)->nullable();
            $table->string('transparency_urine', 10)->default(null)->nullable();

            $table->string('reaction_urine', 10)->default(null)->nullable();
            $table->string('gravity_urine', 10)->default(null)->nullable();
            $table->string('glucose_urine', 10)->default(null)->nullable();
            $table->string('protein_urine', 10)->default(null)->nullable();

            $table->string('wbc_urine', 10)->default(null)->nullable();
            $table->string('rbc_urine', 10)->default(null)->nullable();
            $table->string('epithelial_cells_urine', 10)->default(null)->nullable();
            $table->string('bacteria_urine', 10)->default(null)->nullable();
            $table->string('mucus_thread_urine', 10)->default(null)->nullable();

            $table->string('amorphous_urates_urine', 10)->default(null)->nullable();
            $table->string('amorphous_phosphates_urine', 10)->default(null)->nullable();
            $table->string('calciun_oxalates_urine', 10)->default(null)->nullable();
            $table->string('triple_phosphates_urine', 10)->default(null)->nullable();
            $table->string('uric_acid_urine', 10)->default(null)->nullable();
            $table->string('others_urine', 10)->default(null)->nullable();

            $table->string('hyaline_cast_urine', 10)->default(null)->nullable();
            $table->string('wbc_cast_urine', 10)->default(null)->nullable();
            $table->string('rbc_cast_urine', 10)->default(null)->nullable();
            $table->string('granular_cast_urine', 10)->default(null)->nullable();
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
