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
        Schema::create('tele_medicine_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id');
            $table->foreignId('doctor_id');
            $table->date('date');
            $table->foreignId('slot_id');
            $table->string('status')->default('pending');
            $table->uuid('uuid')->unique();
            $table->string('channel_name');
            $table->string('notes');
            $table->text('token');
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
        Schema::dropIfExists('tele_medicine_schedules');
    }
};
