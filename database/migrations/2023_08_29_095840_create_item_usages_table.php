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
        Schema::create('item_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->default(null)->nullable();
            $table->foreignId('item_id');
            $table->foreignId('inventory_id');
            $table->float('quantity')->default(0);
            $table->string('type');
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
        Schema::dropIfExists('item_usages');
    }
};
