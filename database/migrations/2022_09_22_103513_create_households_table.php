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
        Schema::create('households', function (Blueprint $table) {
            $table->id();
            $table->string('province');
            $table->string('city');
            $table->string('zone')->nullable();
            $table->string('barangay');
            $table->string('purok')->nullable();
            $table->string('street');
            $table->string('house_number')->nullable();
            $table->string('house_id');
            $table->foreignId('head_id')->nullable();
            $table->foreignId('surveyor_id')->nullable();
            $table->double('lat')->nullable();
            $table->double('lng')->nullable();
            $table->double('altitude')->nullable();
            $table->double('accuracy')->nullable();
            $table->dateTime('date_interview');
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
        Schema::dropIfExists('households');
    }
};
