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

        Schema::table('house_raw_answers', function (Blueprint $table) {
            $table->string('fp_natural')->nullable();
            $table->string('memberssolo')->nullable();
        });

        Schema::table('household_characteristics', function (Blueprint $table) {
            $table->string('memberssolo')->nullable();
            $table->string('fp_natural')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('household_characteristics', function (Blueprint $table) {
            $table->dropColumn(['fp_natural','memberssolo']);

        });

        Schema::table('house_raw_answers', function (Blueprint $table) {
            $table->dropColumn(['fp_natural','memberssolo']);
        });
    }
};
