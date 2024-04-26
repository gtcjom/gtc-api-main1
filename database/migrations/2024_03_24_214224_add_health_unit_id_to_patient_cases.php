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
        Schema::table('patient_cases', function (Blueprint $table) {
            $table->string('health_unit_id')->nullable();
            $table->boolean('is_referral')->default(false);
            $table->string('referred_type')->nullable();
            $table->json('referral_data')->nullable();
            $table->boolean('referral_accepted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patient_cases', function (Blueprint $table) {
            $table->dropColumn('health_unit_id');
            $table->dropColumn('is_referral');
            $table->dropColumn('referred_type');
            $table->dropColumn('referral_data');
            $table->dropColumn('referral_accepted');
        });
    }
};
