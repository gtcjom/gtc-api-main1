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
        Schema::create('patient_queues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id');
            $table->foreignId('patient_id');
            $table->foreignId('to_intended_id')->default(null)->nullable();
            $table->string('purpose');
            $table->string('send_to');
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
        Schema::dropIfExists('patient_queues');
    }
};
