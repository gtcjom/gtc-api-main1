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
            $table->string('cloud_id', 50)->nullable()->after('id');
            $table->boolean('unsent')->default(0)->after('cloud_id');
            $table->boolean('verified')->default(0)->after('unsent');
            $table->timestamp('verified_at')->nullable()->after('verified');
            $table->timestamp('last_updated')->nullable();
            $table->string('verified_by', 20)->nullable()->after('verified_at');
            $table->string('verified_by_entity', 5)->nullable()->after('verified_by');
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
            $table->dropColumn('cloud_id');
            $table->dropColumn('unsent');
            $table->dropColumn('verified');
            $table->dropColumn('verified_at');
            $table->dropColumn('last_updated');
            $table->dropColumn('verified_by');
            $table->dropColumn('verified_by_entity');
        });
    }
};
