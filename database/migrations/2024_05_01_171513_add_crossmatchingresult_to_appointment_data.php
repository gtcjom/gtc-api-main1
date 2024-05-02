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
            $table->string('blood_type_crossmatch', 10)->default(null)->nullable();
            $table->string('method_crossmatch', 10)->default(null)->nullable();
            $table->string('serial_number_crossmatch', 10)->default(null)->nullable();
            $table->string('donor_blood_type', 10)->default(null)->nullable();
            $table->string('source_crossmatch', 10)->default(null)->nullable();
            $table->string('component_crossmatch', 10)->default(null)->nullable();
            $table->string('content_crossmatch', 10)->default(null)->nullable();
            $table->string('extract_date_crossmatch', 10)->default(null)->nullable();
            $table->string('expiry_date_crossmatch', 10)->default(null)->nullable();
            $table->string('cossmatching_result_crossmatch', 10)->default(null)->nullable();
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
            $table->dropColumn('blood_type_crossmatch');
            $table->dropColumn('method_crossmatch');
            $table->dropColumn('serial_number_crossmatch');
            $table->dropColumn('donor_blood_type');
            $table->dropColumn('source_crossmatch');
            $table->dropColumn('component_crossmatch');
            $table->dropColumn('content_crossmatch');
            $table->dropColumn('extract_date_crossmatch');
            $table->dropColumn('expiry_date_crossmatch');
            $table->dropColumn('cossmatching_result_crossmatch');
        });
    }
};
