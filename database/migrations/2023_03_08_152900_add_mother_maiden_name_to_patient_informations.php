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
        Schema::table('patient_information', function (Blueprint $table) {

            $table->string('employment',20)->default('')->nullable();
            $table->string('pwd',5)->default('no')->nullable();
            $table->string('sss_member',5)->default('no')->nullable();
            $table->string('bloodtransfuse',5)->default('no')->nullable();
            $table->string('smoke',5)->default('no')->nullable();
            $table->string('sexactive',5)->default('no')->nullable();
            $table->string('howmanysex',5)->default('')->nullable();
            $table->string('received_tetanus',5)->default('no')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patient_information', function (Blueprint $table) {
            //
            $table->dropColumn(['employment','pwd','sss_member','bloodtransfuse','smoke','sexactive','howmanysex','received_tetanus']);
        });
    }
};
