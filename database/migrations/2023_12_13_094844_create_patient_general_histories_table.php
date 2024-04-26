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
        Schema::create('patient_general_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->nullable();
            $table->foreignId('patient_id')->nullable();
            $table->string('hypertension')->nullable();
            $table->string('stroke')->nullable();
            $table->string('heart_disease')->nullable();
            $table->string('high_cholesterol')->nullable();
            $table->string('bleeding_disorders')->nullable();
            $table->string('diabetes')->nullable();
            $table->string('kidney_disease')->nullable();
            $table->string('liver_disease')->nullable();
            $table->string('copd')->nullable();
            $table->string('asthma')->nullable();
            $table->string('mental_neurological_substance_abuse')->nullable();
            $table->string('mental_neurological_substance_abuse_details')->nullable();
            $table->string('cancer')->nullable();
            $table->string('cancer_details')->nullable();
            $table->string('others')->nullable();
            $table->string('others_details')->nullable();

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
        Schema::dropIfExists('patient_general_histories');
    }
};
