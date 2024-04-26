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
        Schema::table('vitals', function (Blueprint $table) {
			$table->float('blood_pressure')->nullable()->after('respiratory');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vitals', function (Blueprint $table) {
            $table->dropColumn('blood_pressure');
        });
    }
};
