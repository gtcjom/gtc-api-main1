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
        Schema::table('appointment_data', function (Blueprint $table) {
            //
            $table->string('rhu_ultrasound_result', 10)->default(null)->nullable();
            $table->string('rhu_ultrasound_remarks', 10)->default(null)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appointment_data', function (Blueprint $table) {
            //
            $table->dropColumn('rhu_ultrasound_result');
            $table->dropColumn('rhu_ultrasound_remarks');
        });
    }
};
