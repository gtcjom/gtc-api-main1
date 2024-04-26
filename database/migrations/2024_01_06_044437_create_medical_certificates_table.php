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
        Schema::create('medical_certificates', function (Blueprint $table) {
            $table->id();
            $table->string('diagnosis')->nullable();
            $table->string('notes')->nullable();
            $table->string('status')->nullable();
            $table->string('remarks')->nullable();
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->foreignId('doctor_id')->nullable();
            $table->foreignId('patient_id')->nullable();
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
        Schema::dropIfExists('medical_certificates');
    }
};
