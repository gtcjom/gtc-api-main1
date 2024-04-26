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
        Schema::create('operating_room_charts', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('clinic_id')->nullable();
			$table->unsignedBigInteger('patient_id');
			$table->date('date');
			$table->time('time')->nullable();
			$table->text('procedure')->nullable();
			$table->string('status')->default('operating_room');
			$table->tinyInteger('priority')->default('0');
			$table->string('room_number')->nullable();
			$table->unsignedBigInteger('appointment_id')->nullable();
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
        Schema::dropIfExists('operating_room_charts');
    }
};
