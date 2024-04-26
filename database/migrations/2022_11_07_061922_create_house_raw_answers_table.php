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
        Schema::create('house_raw_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('household_id');
            $table->string('building_type')->nullable();
            $table->string('roof_materials')->nullable();
            $table->string('wall_materials')->nullable();
            $table->string('overseas_members')->nullable();
            $table->string('nuclear_families')->nullable();
            $table->string('no_of_hh')->nullable();
            $table->string('fam_plan')->nullable();
            $table->string('fp')->nullable();
            $table->string('fp_method')->nullable();
            $table->string('undecide')->nullable();
            $table->string('indisease')->nullable();
            $table->string('no_intent')->nullable();
            $table->string('pregnant')->nullable();
            $table->string('pregnant_number')->nullable();
            $table->string('solo')->nullable();
            $table->string('pwd')->nullable();
            $table->string('disabled_number')->nullable();
            $table->string('pets')->nullable();
            $table->string('number_pets')->nullable();
            $table->string('pet_vaccine_date')->nullable();
            $table->string('pet_vax')->nullable();
            $table->string('number_of_hh')->nullable();
            $table->string('main_source')->nullable();
            $table->string('drink_water')->nullable();
            $table->string('hh_toilet')->nullable();
            $table->string('status')->nullable();
            $table->string('residence_area')->nullable();
            $table->string('electric')->nullable();
            $table->string('electric_housing')->nullable();
            $table->string('com_channel')->nullable();
            $table->string('housingother')->nullable();
            $table->string('garbage_disposal')->nullable();
            $table->string('collector')->nullable();
            $table->string('often_garbage')->nullable();
            $table->string('collectiontimesother')->default('')->nullable();
            $table->string('volume_waste')->nullable();
            $table->string('disposalothers')->nullable();
            $table->string('jobList')->nullable();
            $table->string('jobIncome')->nullable();
            $table->string('calamity_experienced')->nullable();
            $table->string('calamity')->nullable();
            $table->string('assistancecalamity')->nullable();

























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
        Schema::dropIfExists('house_raw_answers');
    }
};
