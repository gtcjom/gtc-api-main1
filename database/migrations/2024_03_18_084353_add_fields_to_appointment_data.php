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
            $table->string('cloud_id')->nullable();
            $table->timestamp('last_updated')->nullable();
            $table->string('refer_to_doctor_name')->default("")->nullable();
            $table->string('patient_name')->default("")->nullable();
            $table->string('serviced_by_name')->default("")->nullable();
            $table->string('referred_by_name')->default("")->nullable();
            $table->string('referred_type')->default("")->nullable();
            $table->string('approved_by_name')->default("")->nullable();
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
            $table->dropColumn('cloud_id');
            $table->dropColumn('last_updated');
            $table->dropColumn('refer_to_doctor_name');
            $table->dropColumn('patient_name');
            $table->dropColumn('serviced_by_name');
            $table->dropColumn('referred_by_name');
            $table->dropColumn('referred_type');
            $table->dropColumn('approved_by_name');
        });
    }
};
