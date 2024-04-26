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
            //
            $table->unsignedInteger('bhs_id')->nullable();
            $table->unsignedInteger('rhu_id')->nullable();
            $table->string('status')->nullable();
            $table->string('referred_to')->nullable();
            $table->string('serviced_by')->nullable();
            $table->string('specimen_picture')->nullable();

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
            //
            $table->dropColumn('bhs_id');
            $table->dropColumn('rhu_id');
            $table->dropColumn('status');
            $table->dropColumn('referred_to');
            $table->dropColumn('serviced_by');
            $table->dropColumn('specimen_picture');
        });
    }
};
