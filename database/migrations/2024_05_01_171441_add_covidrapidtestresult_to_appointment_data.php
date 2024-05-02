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
            $table->string('type_specimen', 10)->default(null)->nullable();
            $table->string('test_kit', 10)->default(null)->nullable();
            $table->string('method', 10)->default(null)->nullable();
            $table->string('result', 10)->default(null)->nullable();
            $table->string('value', 10)->default(null)->nullable();
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
            $table->dropColumn('type_specimen');
            $table->dropColumn('test_kit');
            $table->dropColumn('method');
            $table->dropColumn('result');
            $table->dropColumn('value');
        });
    }
};
