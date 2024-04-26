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
        Schema::create('patient_cases', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();
            $table->string('cloud_id')->nullable();
            $table->unsignedBigInteger('appointment_id')->nullable();
            $table->unsignedBigInteger('patient_id');
            $table->string('patient_cloud_id')->nullable();
            $table->string('phic_id')->nullable();
            $table->string('prefix',10);
            $table->string('code',5);
            $table->string('suffix',2);
            $table->date('case_date');
            $table->string('patient_name',50);
            $table->date('dob');
            $table->string('address',150);
            $table->string('gender',20);
            $table->string('case_picture',200)->nullable();
            $table->string('mode_of_consultation',50)->nullable()->default('');
            $table->string('consultation_type',50)->nullable()->default('');
            $table->text('chief_complaint')->nullable();
            $table->text('history_of_present_illness')->nullable();
            $table->json('general_history')->nullable();
            $table->json('medical_and_surgical_history')->nullable();
            $table->json('environmental_history')->nullable();
            $table->json('personal_social_history')->nullable();
            $table->json('patient_symptoms')->nullable();
            $table->json('entities')->nullable();
            $table->json('tb_symptoms')->nullable();
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
        Schema::dropIfExists('patient_cases');
    }
};
