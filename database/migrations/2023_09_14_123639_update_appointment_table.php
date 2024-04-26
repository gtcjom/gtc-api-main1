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

            $table->foreignId('referred_by')->default(null)->nullable();
            $table->string('reason')->default(null)->nullable();
            $table->string('health_insurrance_coverage')->default(null)->nullable();
            $table->string('health_insurrance_coverage_if_yes_type')->default(null)->nullable();
            $table->string('action_taken')->default(null)->nullable();
            $table->string('impression')->default(null)->nullable();
            $table->string('lab_findings')->default(null)->nullable();
            $table->string('clinical_history')->default(null)->nullable();
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
            $table->dropColumn('referred_by');
            $table->dropColumn('reason');
            $table->dropColumn('health_insurrance_coverage');
            $table->dropColumn('health_insurrance_coverage_if_yes_type');
            $table->dropColumn('action_taken');
            $table->dropColumn('impression');
            $table->dropColumn('lab_findings');
            $table->dropColumn('clinical_history');
        });
    }
};
