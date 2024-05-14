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
        Schema::create('operation_procedures', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('patient_id');
            $table->string('operation_number')->nullable();
            $table->date('operation_date')->nullable();
            $table->time('operation_time')->nullable();
            $table->string('procedure_test_type')->nullable();
            $table->string('procedure')->nullable();
            $table->string('operation_notes')->nullable();
            $table->unsignedBigInteger('doctor_id')->nullable();
            $table->string('operation_status')->nullable();
            $table->foreignId('health_unit_id')->default(null)->nullable();
            $table->foreignId('appointment_id')->default(null)->nullable();
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
        Schema::dropIfExists('operation_procedures');
    }
};
