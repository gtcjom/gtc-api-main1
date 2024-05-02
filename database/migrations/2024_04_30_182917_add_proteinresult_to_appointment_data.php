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
            //Total Protein
            $table->string('total_protein', 10)->default(null)->nullable();
            $table->string('albumin', 10)->default(null)->nullable();
            $table->string('globulin', 10)->default(null)->nullable();
            $table->string('ag_ratio', 10)->default(null)->nullable();
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
            //total protein
            $table->dropColumn('total_protein');
            $table->dropColumn('albumin');
            $table->dropColumn('globulin');
            $table->dropColumn('ag_ratio');
        });
    }
};
