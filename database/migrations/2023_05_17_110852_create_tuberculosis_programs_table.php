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
        Schema::create('tuberculosis_programs', function (Blueprint $table) {
            $table->id();
			$table->string('patient_id');
			$table->string('address')->nullable();
			$table->string('program')->nullable();
			$table->string('barangay_id')->nullable();
			$table->date('brgy_refferal_date')->nullable();
			$table->string('refer_by_brgy_asst')->nullable();
			$table->string('brgy_notes')->nullable();
			$table->string('rhu')->nullable();
			$table->date('rhu_refferal_date')->nullable();
			$table->string('rhu_notes')->nullable();
			$table->string('refer_by_rhu')->nullable();
			$table->string('status')->nullable();
			$table->string('program_status')->nullable();
			$table->datetime('date_approved')->nullable();
			$table->string('approved_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tuberculosis_programs');
    }
};
