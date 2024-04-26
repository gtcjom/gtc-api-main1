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
        Schema::create('vitals', function (Blueprint $table) {
            $table->id();
            $table->float('temperature');
            $table->float('blood_pressure');
            $table->float('respiratory')->nullable();
            $table->float('uric_acid')->nullable();
            $table->float('cholesterol')->nullable();
            $table->float('glucose')->nullable();
            $table->float('pulse')->nullable();
            $table->float('weight')->nullable();
            $table->float('height')->nullable();
            $table->foreignId('patient_id');
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
        Schema::dropIfExists('vitals');
    }
};
