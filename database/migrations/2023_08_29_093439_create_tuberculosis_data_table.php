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
        Schema::create('tuberculosis_data', function (Blueprint $table) {
            $table->id();
            $table->boolean('cough_for_3_weeks_or_longer')->default(false)->nullable();
            $table->boolean('coughing_up_blood_or_mucus')->default(false)->nullable();
            $table->boolean('chest_pain')->default(false)->nullable();
            $table->boolean('pain_with_breathing_or_coughing')->default(false)->nullable();
            $table->boolean('fever')->default(false)->nullable();
            $table->boolean('chills')->default(false)->nullable();
            $table->boolean('night_sweats')->default(false)->nullable();
            $table->boolean('weight_loss')->default(false)->nullable();
            $table->boolean('not_wanting_to_eat')->default(false)->nullable();
            $table->boolean('tiredness')->default(false)->nullable();
            $table->boolean('not_feeling_well_in_general')->default(false)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tuberculosis_data');
    }
};
