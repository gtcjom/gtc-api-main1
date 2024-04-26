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
        Schema::create('laboratory_orders', function (Blueprint $table) {
            $table->id();
			$table->string('order_number')->nullable();
			$table->date('order_date')->nullable();
			$table->unsignedBigInteger('patient_id');
			$table->unsignedBigInteger('laboratory_test_id');
			$table->unsignedBigInteger('doctor_id')->nullable();
			$table->unsignedBigInteger('clinic_id')->nullable();
			$table->string('notes')->nullable();
			$table->string('lab_result_description')->nullable();
			$table->unsignedBigInteger('processed_by')->nullable();
			$table->datetime('date_processed')->nullable();
			$table->string('order_status')->nullable();
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
        Schema::dropIfExists('laboratory_orders');
    }
};
