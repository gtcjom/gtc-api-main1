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
            //Electrolytes
            $table->string('sodium', 10)->default(null)->nullable();
            $table->string('potassium', 10)->default(null)->nullable();
            $table->string('calcium_total', 10)->default(null)->nullable();
            $table->string('calcium_ionized', 10)->default(null)->nullable();
            $table->string('ph', 10)->default(null)->nullable();
            $table->string('chloride', 10)->default(null)->nullable();

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
            //electrolytes
            $table->dropColumn('sodium');
            $table->dropColumn('potassium');
            $table->dropColumn('calcium_total');
            $table->dropColumn('calcium_ionized');
            $table->dropColumn('ph');
            $table->dropColumn('chloride');
        });
    }
};
