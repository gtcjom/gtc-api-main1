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
        Schema::create('patient_pmrf_information', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->default(null)->nullable();
            $table->json('personal_details')->nullable();
            $table->json('address_contact_details')->nullable();
            $table->json('declaration_dependents')->nullable();
            $table->json('member_type')->nullable();
            $table->json('updating_amendment')->nullable();
            $table->json('for_philhealth')->nullable();
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
        Schema::dropIfExists('patient_pmrf_information');
    }
};
