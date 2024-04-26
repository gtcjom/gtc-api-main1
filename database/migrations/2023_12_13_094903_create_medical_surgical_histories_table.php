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
        Schema::create('medical_surgical_histories', function (Blueprint $table) {
            $table->id();



            $table->foreignId('appointment_id')->nullable();
            $table->foreignId('patient_id')->nullable();

            $table->string('asthma_history')->nullable();
            $table->string('asthma_history_details')->nullable();
            $table->string('allergies')->nullable();
            $table->string('allergies_details')->nullable();
            $table->string('allergies_to_medicine')->nullable();
            $table->string('allergies_to_medicine_details')->nullable();
            $table->string('immunization')->nullable();
            $table->string('immunization_details')->nullable();
            $table->string('injuries_accidents')->nullable();
            $table->string('injuries_accidents_details')->nullable();
            $table->string('hearing_problems')->nullable();
            $table->string('hearing_problems_details')->nullable();
            $table->string('vision_problems')->nullable();
            $table->string('vision_problems_details')->nullable();
            $table->string('heart_disease_history')->nullable();
            $table->string('heart_disease_history_details')->nullable();
            $table->string('neurological_substance_use_conditions')->nullable();
            $table->string('neurological_substance_use_conditions_details')->nullable();
            $table->string('cancer_history')->nullable();
            $table->string('cancer_history_details')->nullable();
            $table->string('other_organ_disorders')->nullable();
            $table->string('other_organ_disorders_details')->nullable();
            $table->string('previous_hospitalizations')->nullable();
            $table->string('previous_hospitalizations_details')->nullable();
            $table->string('previous_surgeries')->nullable();
            $table->string('previous_surgeries_details')->nullable();
            $table->string('other_medical_surgical_history')->nullable();
            $table->string('other_medical_surgical_history_details')->nullable();



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
        Schema::dropIfExists('medical_surgical_histories');
    }
};
