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
        Schema::create('patient_raw_answers', function (Blueprint $table) {
            $table->id();
            $table->string('immunization')->nullable();
            $table->string('vax_status')->nullable();
            $table->string('crime_victim')->nullable();
            $table->string('crime_locations')->nullable();
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
        Schema::dropIfExists('patient_raw_answers');
    }
};
