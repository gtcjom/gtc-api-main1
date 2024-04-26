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
        Schema::create('patient_information', function (Blueprint $table) {
            $table->id();
            $table->string('registered_civil');
            $table->string('marital_status');
            $table->string('ethnicity');
            $table->double('month_cash')->nullable();
            $table->string('philhealth')->nullable();
            $table->text('immunization')->nullable();
            $table->string('mmr')->nullable();
            $table->string('hpv')->nullable();
            $table->string('covid_vaccinated');
            $table->string('vax_status')->nullable();
            $table->string('dental')->nullable();
            $table->string('cleft_lip')->default('no');
            $table->string('donate_blood')->default('no');
            $table->string('solo_parent')->default('no');
            $table->string('physical_mental')->default('no');
            $table->string('disease')->default('no');
            $table->string('other_disease')->nullable();
            $table->string('past_victim')->default('no');
            $table->string('crime_victim')->nullable();
            $table->string('crime_locations')->nullable();
            $table->string('children_nutrition_age')->nullable();
            $table->string('children_nutrition_age_old_new')->nullable();
            $table->string('children_nutrition_wt')->nullable();
            $table->date('date_nutrition')->nullable();
            $table->string('care_center')->nullable();
            $table->string('dental_carries')->nullable();
            $table->string('de_worm')->nullable();
            $table->string('height_cm')->nullable();
            $table->string('sth')->nullable();
            $table->integer('ob_gravida')->default(0);
            $table->integer('ob_parity')->default(0);
            $table->integer('ob_abortion')->default(0);
            $table->integer('ob_living')->default(0);
            $table->string('pregnant')->default('no');
            $table->string('visit_clinic')->nullable();
            $table->date('date_visit_clinic')->nullable();
            $table->string('selectoralpolio')->nullable();
            $table->string('specifypenta')->nullable();
            $table->string('selectmmr')->nullable();
            $table->string('selecthpv')->nullable();
            $table->string('donateblood')->default('no')->nullable();
            $table->text('comnondisease')->nullable();
            $table->text('nondisease')->nullable();
            $table->date('tbdate')->nullable();
            $table->string('treatment')->nullable();
            $table->string('diabetes')->nullable();
            $table->string('hypertension')->nullable();
            $table->string('dismember')->nullable();
            $table->string('hhpwd')->nullable();
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
        Schema::dropIfExists('patient_information');
    }
};
