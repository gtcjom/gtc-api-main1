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
        Schema::table('laboratory_results', function (Blueprint $table) {
            $table->string('results')->nullable()->after('remarks');
            $table->string('laboratory_order_type')->nullable()->after('laboratory_order_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('laboratory_results', function (Blueprint $table) {
            $table->dropColumn(['results', 'laboratory_order_type']);
        });
    }
};
