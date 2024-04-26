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
        Schema::table('consignment_order_locations', function (Blueprint $table) {
            //

            $table->unsignedBigInteger('scheduled_by')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->unsignedBigInteger('checked_by')->nullable();
            $table->unsignedBigInteger('processed_by')->nullable();
            $table->unsignedBigInteger('received_by')->nullable();
            $table->unsignedBigInteger('delivered_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('consignment_order_locations', function (Blueprint $table) {
            //
            $table->dropColumn([
                'processed_by',
                'scheduled_by',
                'approved_by',
                'checked_by',
                'received_by',
                'delivered_by',
            ]);
        });
    }
};
