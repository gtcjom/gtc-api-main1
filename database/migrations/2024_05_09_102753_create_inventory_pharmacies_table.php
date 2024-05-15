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
        Schema::create('inventory_pharmacies', function (Blueprint $table) {
            $table->id();
            $table->date('pharmacy_date')->nullable();
            $table->string('pharmacy_supplies')->nullable();
            $table->string('pharmacy_stocks')->nullable();
            $table->string('pharmacy_price')->nullable();
            $table->string('pharmacy_status')->nullable();
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
        Schema::dropIfExists('inventory_pharmacies');
    }
};
