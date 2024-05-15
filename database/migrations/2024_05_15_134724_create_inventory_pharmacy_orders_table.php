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
        Schema::create('inventory_pharmacy_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            // $table->unsignedBigInteger('inventory_pharmacy_id');
            $table->unsignedBigInteger('doctor_id')->nullable();
            $table->date('date')->nullable();
            $table->string('supplies')->nullable();
            $table->string('quantity')->nullable();
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
        Schema::dropIfExists('inventory_pharmacy_orders');
    }
};
