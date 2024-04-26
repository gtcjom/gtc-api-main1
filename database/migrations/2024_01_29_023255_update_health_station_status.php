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
        Schema::table('health_units', function (Blueprint $table) {
            $table->string('status')->default('active');
            $table->string('region')->nullable();
            $table->string('province')->nullable();
            $table->string('municipality')->nullable();
            $table->string('barangay')->nullable();
            $table->string('street')->nullable();
            $table->string('purok')->nullable();
            $table->string('zip')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('health_units', function (Blueprint $table) {
            //
            $table->dropColumn('status');
            $table->dropColumn('region');
            $table->dropColumn('province');
            $table->dropColumn('municipality');
            $table->dropColumn('barangay');
            $table->dropColumn('street');
            $table->dropColumn('purok');
            $table->dropColumn('zip');
        });
    }
};
