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
            //Lipid Profile
            $table->string('cholesterol', 10)->default(null)->nullable();
            $table->string('triglyceride', 10)->default(null)->nullable();
            $table->string('hdl', 10)->default(null)->nullable();
            $table->string('ldl', 10)->default(null)->nullable();
            $table->string('hbac', 10)->default(null)->nullable();
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
            //lipid
            $table->dropColumn('cholesterol');
            $table->dropColumn('triglyceride');
            $table->dropColumn('hdl');
            $table->dropColumn('ldl');
            $table->dropColumn('hbac');

        });
    }
};
