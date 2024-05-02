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
            $table->string('test_name_misc', 10)->default(null)->nullable();
            $table->string('result_misc', 10)->default(null)->nullable();
            $table->string('remarks_misc', 10)->default(null)->nullable();
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
            $table->dropColumn('test_name_misc');
            $table->dropColumn('result_misc');
            $table->dropColumn('remarks_misc');
        });
    }
};
