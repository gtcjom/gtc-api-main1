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
        Schema::table('patient_queues', function (Blueprint $table) {
            $table->integer('priority')->default(0);
            $table->string('room_number');
            $table->string('type_service');
            $table->foreignId('doctor_id')->nullable();
            $table->foreignId('appointment_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patient_queues', function (Blueprint $table) {
            $table->dropColumn(['priority','appointment_id','room_number','type_service','doctor_id']);
        });
    }
};
