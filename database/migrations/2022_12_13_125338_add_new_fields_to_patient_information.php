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
            $table->date('lastmensperiod')->nullable();
            $table->string('clinic')->nullable()->default('');
            $table->string('prenatal')->nullable()->default('');
            $table->string('tribe')->nullable()->default('');
            $table->string('noneIP')->nullable()->default('');
            $table->string('tin')->nullable()->default('');
            $table->string('coopmember')->nullable()->default('');
            $table->string('phic_no')->nullable()->default('');
            $table->string('phic')->nullable()->default('');
            $table->string('philsys')->nullable()->default('');
            $table->string('phicdirect')->nullable()->default('');
            $table->string('familycounselling')->nullable()->default('');


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
            $table->dropColumn([
                'lastmensperiod',
                'clinic',
                'prenatal',
                'tribe',
                'noneIP',
                'tin',
                'coopmember',
                'phic_no',
                'phic',
                'phicdirect',
                'philsys'
            ]);
        });
    }
};
