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
        Schema::create('consignment_orders', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('cof_number')->nullable();
            $table->string('consignor')->nullable();
            $table->string('term')->nullable();
            $table->string('hci_name')->nullable();
            $table->string('hci_number')->nullable();
            $table->string('to_location_type')->nullable();
            $table->unsignedBigInteger('to_location_id')->nullable();
            $table->string('from_location_type')->nullable();
            $table->unsignedBigInteger('from_location_id')->nullable();
            $table->string('status')->nullable();
            $table->unsignedBigInteger('scheduled_by')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->unsignedBigInteger('checked_by')->nullable();
            $table->unsignedBigInteger('received_by')->nullable();
            $table->unsignedBigInteger('delivered_by')->nullable();
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
        Schema::dropIfExists('consignment_orders');
    }
};
