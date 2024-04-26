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
        Schema::create('house_characteristics', function (Blueprint $table) {
            $table->id();
            $table->string('building_type');
            $table->string('roof_materials');
            $table->string('wall_materials');
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
        Schema::dropIfExists('house_characteristics');
    }
};
