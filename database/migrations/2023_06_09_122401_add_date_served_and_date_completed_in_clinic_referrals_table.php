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
        Schema::table('clinic_referrals', function (Blueprint $table) {
            $table->datetime('date_served')->nullable()->after('date_received');
            $table->datetime('date_completed')->nullable()->after('date_served');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clinic_referrals', function (Blueprint $table) {
            $table->dropColumn(['date_served', 'date_completed']);
        });
    }
};
