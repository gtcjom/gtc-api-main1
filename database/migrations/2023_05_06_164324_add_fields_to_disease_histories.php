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
        Schema::table('disease_histories', function (Blueprint $table) {
            $table->boolean('pui')->default(true);
            $table->boolean('confirmed')->default(false);
            $table->boolean('treated')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('disease_histories', function (Blueprint $table) {
            $table->dropColumn(['pui', 'confirmed', 'treated']);
        });
    }
};
