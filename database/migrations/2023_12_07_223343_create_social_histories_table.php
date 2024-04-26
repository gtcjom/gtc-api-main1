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
        Schema::create('social_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->nullable();
            $table->foreignId('patient_id')->nullable();
            $table->string('intake_high_sugar')->nullable();
            $table->string('intake_high_sugar_details')->default('')->nullable();
            $table->string('take_supplements')->nullable();
            $table->string('take_supplements_details')->nullable();
            $table->string('deworming_6months')->nullable();
            $table->string('deworming_6months_details')->nullable();
            $table->string('flouride_toothpaste')->nullable();
            $table->string('last_dental_check_up')->nullable();
            $table->string('physical_activity')->nullable();
            $table->string('daily_screen_time')->nullable();
            $table->string('violence_injuries')->nullable();
            $table->string('violence_injuries_details')->nullable();
            $table->string('bully_harassment')->nullable();
            $table->string('bully_harassment_details')->nullable();
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
        Schema::dropIfExists('social_histories');
    }
};
