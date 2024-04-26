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
			$table->unsignedBigInteger('hospital_id')->nullable()->after('refer_by_rhu');
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
            $table->dropColumn(['hospital_id']);
        });
    }
};
