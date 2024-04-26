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
        Schema::create('operating_room_healthcare_professionals', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('operating_room_chart_id');
			$table->unsignedBigInteger('doctor_id');
			$table->string('title')->nullable();
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
        Schema::dropIfExists('operating_room_healthcare_professionals');
    }
};
