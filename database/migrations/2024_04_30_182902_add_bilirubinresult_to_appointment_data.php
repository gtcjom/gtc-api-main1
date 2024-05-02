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
            //Bilirubin
            $table->string('total_bilirubin', 10)->default(null)->nullable();
            $table->string('direct_bilirubin', 10)->default(null)->nullable();
            $table->string('indirect_bilirubin', 10)->default(null)->nullable();
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
            //bilirubin
            $table->dropColumn('total_bilirubin');
            $table->dropColumn('direct_bilirubin');
            $table->dropColumn('indirect_bilirubin');
        });
    }
};
