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
        Schema::table('patients', function (Blueprint $table) {

            $table->string('floor')->default(null)->nullable();
            $table->string('direct_contributor')->default(null)->nullable();
            $table->string('indirect_contributor')->default(null)->nullable();
            $table->string('profession')->default(null)->nullable();
            $table->string('salary')->default(null)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patients', function (Blueprint $table) {
            //
            $table->dropColumn('floor');
            $table->dropColumn('direct_contributor');
            $table->dropColumn('indirect_contributor');
            $table->dropColumn('profession');
            $table->dropColumn('salary');
        });
    }
};
