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
        Schema::create('clinic_referrals', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('patient_id');
			$table->date('referral_date');
			$table->string('notes')->nullable();
			$table->unsignedBigInteger('added_by')->nullable();
			$table->unsignedBigInteger('from_clinic_id');
			$table->unsignedBigInteger('to_clinic_id');
			$table->string('diagnosis')->nullable();
			$table->unsignedBigInteger('received_by')->nullable();
			$table->unsignedBigInteger('date_received')->nullable();
			$table->string('status')->default('pending');
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
        Schema::dropIfExists('clinic_referrals');
    }
};
