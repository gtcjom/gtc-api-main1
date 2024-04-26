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
        Schema::table('patients', function (Blueprint $table) {
            $table->double('lat')->nullable();
            $table->double('lng')->nullable();
            $table->foreignId('purok_id')->nullable();
            $table->foreignId('barangay_id')->nullable();
            $table->foreignId('municipality_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn(['lat','lng','purok_id','municipality_id','barangay_id']);
        });
    }
};
