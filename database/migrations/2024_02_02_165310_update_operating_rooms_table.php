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
        Schema::table('operating_rooms', function (Blueprint $table) {

            $table->foreignId('health_unit_id')->default(null)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('operating_rooms', function (Blueprint $table) {
            //
            $table->dropColumn('health_unit_id');
        });
    }
};
