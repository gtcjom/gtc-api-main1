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
        Schema::table('vitals', function (Blueprint $table) {
            $table->string('blood_systolic')->nullable()->default(null);
            $table->string('blood_diastolic')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vitals', function (Blueprint $table) {
            $table->dropColumn(['blood_systolic','blood_diastolic']);

        });
    }
};
