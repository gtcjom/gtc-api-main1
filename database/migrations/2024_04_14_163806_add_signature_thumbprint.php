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
        Schema::table('patient_pmrf_information', function (Blueprint $table) {

            $table->string('signature')->nullable();
            $table->string('thumbprint')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patient_pmrf_information', function (Blueprint $table) {
            //
            $table->dropColumn('signature');
            $table->dropColumn('thumbprint');
        });
    }
};
