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
        Schema::table('patient_raw_answers', function (Blueprint $table) {
            $table->string('current_symptoms')->default('')->nullable();
            $table->string('first_degree')->default('')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patient_raw_answers', function (Blueprint $table) {
                $table->dropColumn(['current_symptoms','first_degree']);

        });
    }
};
