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
        Schema::create('environmental_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->nullable();
            $table->foreignId('patient_id')->nullable();
            $table->string('safe_water')->nullable();
            $table->string('satisfactory_waste_disposal')->nullable();
            $table->string('prolong_exposure_biomass_fuel')->nullable();
            $table->string('exposure_tabacco_vape')->nullable();
            $table->string('exposure_tabacco_vape_details')->nullable();
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
        Schema::dropIfExists('environmental_histories');
    }
};
