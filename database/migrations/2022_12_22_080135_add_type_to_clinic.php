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
        Schema::table('clinic', function (Blueprint $table) {
            $table->string('type')->nullable()->default(null);
            $table->foreignId('municipality_id')->nullable()->default(null);
            $table->foreignId('barangay_id')->nullable()->default(null);
            $table->foreignId('purok_id')->nullable()->default(null);
            $table->string('street')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clinic', function (Blueprint $table) {
            $table->dropColumn([
                'type',
                'municipality_id',
                'barangay_id',
                'purok_id',
                'street'
            ]);
        });
    }
};
