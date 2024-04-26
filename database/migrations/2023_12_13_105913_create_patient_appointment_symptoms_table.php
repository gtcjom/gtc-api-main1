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
        Schema::create('patient_appointment_symptoms', function (Blueprint $table) {
            $table->id();



            $table->foreignId('appointment_id')->nullable();
            $table->foreignId('patient_id')->nullable();

            $table->string('chest_pain_discomfort_heaviness')->nullble();
            $table->string('difficulty_breathing')->nullble();
            $table->string('deizure_convulsion')->nullble();
            $table->string('unconscious_restless_lethargic')->nullble();
            $table->string('not_oriented_to_time_person_place')->nullble();
            $table->string('bluish_discoloration_of_skin_lips')->nullble();
            $table->string('act_of_self_harm_suicide')->nullble();
            $table->string('acute_fracture_dislocation_injuries')->nullble();
            $table->string('signs_of_abuse')->nullble();
            $table->string('severe_abdominal_pain')->nullble();
            $table->string('persistent_vomiting')->nullble();
            $table->string('persistent_diarrhea')->nullble();
            $table->string('unable_to_tolerate_fluids')->nullble();

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
        Schema::dropIfExists('patient_appointment_symptoms');
    }
};
