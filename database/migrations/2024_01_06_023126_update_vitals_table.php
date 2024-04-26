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
        Schema::table('vitals', function (Blueprint $table) {
            //

            $table->string('bmi')->nullable();
            $table->string('height_for_age')->nullable();
            $table->string('weight_for_age')->nullable();
            $table->string('bloody_type')->nullable();
            $table->string('oxygen_saturation')->nullable();
            $table->string('heart_rate')->nullable();
            $table->string('regular_rhythm')->nullable();
            $table->string('covid_19')->nullable();
            $table->string('tb')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vitals', function (Blueprint $table) {
            $table->dropColumn('bmi');
            $table->dropColumn('height_for_age');
            $table->dropColumn('weight_for_age');
            $table->dropColumn('bloody_type');
            $table->dropColumn('oxygen_saturation');
            $table->dropColumn('heart_rate');
            $table->dropColumn('regular_rhythm');
            $table->dropColumn('covid_19');
            $table->dropColumn('tb');
        });
    }
};
