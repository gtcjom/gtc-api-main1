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
        Schema::table('tuberculosis_programs', function (Blueprint $table) {
            $table->unsignedBigInteger('municipality_clinic_id')->nullable()->after('rhu_notes');
            $table->unsignedBigInteger('barangay_clinic_id')->nullable()->after('brgy_notes');
            $table->datetime('date_received_by_rhu')->nullable()->after('rhu');
            $table->string('received_by_rhu_id')->nullable()->after('date_received_by_rhu');
            $table->datetime('date_received_by_sph')->nullable()->after('refer_by_rhu');
            $table->string('received_by_sph_id')->nullable()->after('date_received_by_sph');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tuberculosis_programs', function (Blueprint $table) {
            $table->dropColumn([
				'municipality_clinic_id',
				'barangay_clinic_id',
				'date_received_by_rhu',
				'received_by_rhu_id',
				'date_received_by_sph',
				'received_by_sph_id'
			]);
        });
    }
};
