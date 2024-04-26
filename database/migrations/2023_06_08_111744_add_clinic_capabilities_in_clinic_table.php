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
        Schema::table('clinic', function (Blueprint $table) {
            $table->tinyInteger('tuberculosis')->default(0);
            $table->tinyInteger('animal_bites')->default(0);
            $table->tinyInteger('hypertension')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clinic', function (Blueprint $table) {
            $table->dropColumn('tuberculosis');
            $table->dropColumn('animal_bites');
            $table->dropColumn('hypertension');
        });
    }
};
