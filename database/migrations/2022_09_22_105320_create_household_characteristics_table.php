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
        Schema::create('household_characteristics', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('overseas_members')->default(0)->nullable();
            $table->unsignedInteger('nuclear_families')->default(0)->nullable();
            $table->unsignedInteger('no_of_hh')->default(0)->nullable();
            $table->string('fam_plan')->nullable();
            $table->string('fp')->nullable();
            $table->string('fp_method')->nullable();
            $table->string('no_intent')->nullable();
            $table->string('pregnant')->nullable();
            $table->string('solo')->nullable();
            $table->string('pwd')->nullable();
            $table->string('pets')->nullable();
            $table->string('pet_vax')->default('no')->nullable();
            $table->string('number_pets')->nullable();
            $table->date('pet_vaccine_date')->nullable();
            $table->integer('number_of_hh')->nullable();
            $table->integer("pregnant_number")->default(0)->nullable();
            $table->integer("disabled_number")->default(0)->nullable();
            $table->foreignId('household_id');
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
        Schema::dropIfExists('household_characteristics');
    }
};
