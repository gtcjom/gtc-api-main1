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
        Schema::create('consignment_order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('consignment_order_id')->nullable();
            $table->unsignedBigInteger('consignment_order_location_id')->nullable();
            $table->string('item_id')->nullable();
            $table->string('batch_no');
            $table->date('expiry_date')->nullable();
            $table->date('mfg_date')->nullable();
            $table->float('quantity')->default(0);
            $table->float('price')->default(0);
            $table->float('amount')->default(0);
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
        Schema::dropIfExists('consignment_order_details');
    }
};
