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
        Schema::table('patient_information', function (Blueprint $table) {
            $table->string('phicindirect')->nullable()->default('');
            $table->string('self_earning')->nullable()->default('');
            $table->string('migrant_worker')->nullable()->default('');
            $table->string('arc_id')->nullable()->default('');
            $table->string('foreign_num')->nullable()->default('');
            $table->string('feedingprogram')->nullable()->default('');
            $table->string('mother_maiden_name')->nullable()->default('');
            $table->string('ip_tribe')->nullable()->default('');
            $table->string('otherIPtribe')->nullable()->default('');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patient_information', function (Blueprint $table) {
            $table->dropColumn(['phicindirect','self_earning','migrant_worker',
                'arc_id','foreign_num','mother_maiden_name','feedingprogram',
                'ip_tribe','otherIPtribe'

            ]);
        });
    }
};
