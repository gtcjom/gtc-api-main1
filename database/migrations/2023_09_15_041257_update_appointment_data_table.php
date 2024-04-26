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
            $table->foreignId('released_by')->default(null)->nullable();
            $table->foreignId('xray_result_by')->default(null)->nullable();
            $table->foreignId('lab_result_by')->default(null)->nullable();

            $table->date('date')->default(null)->nullable();
            $table->string('time')->default(null)->nullable();
            $table->boolean('paid')->default(false)->nullable();
            $table->boolean('cleared')->default(false)->nullable();
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
            $table->dropColumn('released_by');
            $table->dropColumn('xray_result_by');
            $table->dropColumn('lab_result_by');
            $table->dropColumn('date');
            $table->dropColumn('time');
            $table->dropColumn('paid');
            $table->dropColumn('cleared');
        });
    }
};
