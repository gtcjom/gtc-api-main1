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
            //
            $table->string('region')->nullable();
            $table->string('province')->nullable();
            $table->string('municipality')->nullable();
            $table->string('barangay')->nullable();
            $table->string('purok')->nullable();
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
            $table->dropColumn('region');
            $table->dropColumn('province');
            $table->dropColumn('municipality');
            $table->dropColumn('barangay');
            $table->dropColumn('purok');
        });
    }
};
