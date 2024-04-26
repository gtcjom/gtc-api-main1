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
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->string('nature_of_visit');
            $table->dateTime('date');
            $table->float('age_in_year');
            $table->float('age_in_month');
            $table->float('age_in_day');
            $table->string('mode_of_transaction',50);
            $table->float('weight');
            $table->float('height');
            $table->float('bmi')->nullable()->default(null);
            $table->string('bmi_category')->nullable()->default(null);
            $table->float('height_for_age')->nullable()->default(null);
            $table->float('weight_for_age')->nullable()->default(null);
            $table->string('attending_provider');
            $table->string('chief_complaint');
            $table->boolean('consent')->default(false);
            $table->string('patient_id');

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
        Schema::dropIfExists('consultations');
    }
};
