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
            $table->unsignedBigInteger('accepted_by')->nullable()->after('date_processed');
            $table->datetime('accepted_at')->nullable()->after('accepted_by');
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
            $table->dropColumn('accepted_by', 'accepted_at');
        });
    }
};
