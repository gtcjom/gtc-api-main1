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
        Schema::create('disease_histories', function (Blueprint $table) {
            $table->id();
            $table->string('patient_id');
            $table->string('disease');
            $table->string('municipality');
            $table->string('barangay');
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->date('date_started');
            $table->date('date_cured')->nullable();
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
        Schema::dropIfExists('disease_histories');
    }
};
