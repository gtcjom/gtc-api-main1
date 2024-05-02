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
            //culture and sensitivity Initial Result
            $table->string('csir_specimen_type', 10)->default(null)->nullable();
            $table->string('csir_specimen_source', 10)->default(null)->nullable();
            $table->string('csir_result', 10)->default(null)->nullable();
            $table->string('csir_remarks', 20)->default(null)->nullable();
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
            //culture and sensitivity Initial Result
            $table->dropColumn('csir_specimen_type');
            $table->dropColumn('csir_specimen_source');
            $table->dropColumn('csir_result');
            $table->dropColumn('csir_remarks');
        });
    }
};
