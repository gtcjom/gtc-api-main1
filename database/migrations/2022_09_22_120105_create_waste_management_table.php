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
        Schema::create('waste_management', function (Blueprint $table) {
            $table->id();
            $table->string('garbage_disposal')->default('')->nullable();
            $table->string('collector')->default('')->nullable();
            $table->string('often_garbage')->default('')->nullable();
            $table->float('volume_waste')->default(0)->nullable();
            $table->string('disposalothers')->nullable();
            $table->string('collectiontimesother')->default('')->nullable();
            $table->foreignId('household_id');
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
        Schema::dropIfExists('waste_management');
    }
};
