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
        Schema::table('laboratory_orders', function (Blueprint $table) {
            $table->string('laboratory_test_type')->nullable()->after('patient_id');
			$table->dropColumn('laboratory_test_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('laboratory_orders', function (Blueprint $table) {
            $table->dropColumn(['laboratory_test_type']);
        });
    }
};
