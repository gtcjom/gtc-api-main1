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
            $table->string('specimen_picture')->default(null)->nullable();
            $table->boolean('for_sph')->default(false)->nullable();
            $table->string('sph_statisfaction')->default(null)->nullable();
            $table->string('sph_serviced_by')->default(null)->nullable();
            $table->string('sph_prescribed_by')->default(null)->nullable();
            $table->string('sph_status')->default(null)->nullable();
            $table->boolean('sph_medicine_released')->default(false)->nullable();
            $table->string('sph_medicine_released_by')->default(null)->nullable();
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
            $table->dropColumn('specimen_picture');
            $table->dropColumn('for_sph');
            $table->dropColumn('sph_statisfaction');
            $table->dropColumn('sph_serviced_by');
            $table->dropColumn('sph_prescribed_by');
            $table->dropColumn('sph_status');
            $table->dropColumn('sph_medicine_released');
            $table->dropColumn('sph_medicine_released_by');
        });
    }
};
