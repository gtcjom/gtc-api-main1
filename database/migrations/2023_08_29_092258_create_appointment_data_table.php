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
        Schema::create('appointment_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bhs_id');
            $table->foreignId('patient_id');
            $table->foreignId('rhu_id')->default(null)->nullable();
            $table->foreignId('vital_id')->default(null)->nullable();
            $table->foreignId('accepted_by_id')->default(null)->nullable();
            $table->foreignId('tb_data_id')->default(null)->nullable();
            $table->text('pre_notes');
            $table->text('post_notes');
            $table->boolean('referable')->default(false);
            $table->boolean('is_done')->default(false);
            $table->boolean('is_tb_positive')->default(false);
            $table->string('selfie')->default(null)->nullable();
            $table->string('satisfaction')->default(null)->nullable();
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
        Schema::dropIfExists('appointment_data');
    }
};
