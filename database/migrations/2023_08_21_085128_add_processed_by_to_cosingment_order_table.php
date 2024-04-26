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
        Schema::table('consignment_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('processed_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('consignment_orders', function (Blueprint $table) {
            //
            $table->dropColumn([
                'processed_by'
            ]);
        });
    }
};
