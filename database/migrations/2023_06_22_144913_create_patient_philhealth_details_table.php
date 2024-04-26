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
        Schema::create('patient_philhealth_details', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('patient_id');
			$table->tinyInteger('employed_private')->default(0)->nullable();
			$table->tinyInteger('employed_government')->default(0)->nullable();
			$table->tinyInteger('professional_practitioner')->default(0)->nullable();
			$table->tinyInteger('self_earning_individual')->default(0)->nullable();
			$table->tinyInteger('individual')->default(0)->nullable();
			$table->tinyInteger('sole_proprietor')->default(0)->nullable();
			$table->tinyInteger('group_enrollment_scheme')->default(0)->nullable();
			$table->tinyText('others_self_earning')->nullable();
			$table->tinyInteger('kasambahay')->default(0)->nullable();
			$table->tinyInteger('family_driver')->default(0)->nullable();
			$table->tinyInteger('migrant_worker')->default(0)->nullable();
			$table->tinyInteger('land_based')->default(0)->nullable();
			$table->tinyInteger('sea_based')->default(0)->nullable();
			$table->tinyInteger('lifetime_member')->default(0)->nullable();
			$table->tinyInteger('dual_citizenship')->default(0)->nullable();
			$table->tinyInteger('foreign_national')->default(0)->nullable();
			$table->string('pra_srrv_no')->nullable();
			$table->string('acr_card_no')->nullable();
			$table->tinyText('profession')->nullable();
			$table->float('monthly_income')->nullable();
			$table->tinyText('proof_of_income')->nullable();
			$table->tinyInteger('listahan')->default(0)->nullable();
			$table->tinyInteger('4ps_or_mcct')->default(0)->nullable();
			$table->tinyInteger('senior_citizen')->default(0)->nullable();
			$table->tinyInteger('pamana')->default(0)->nullable();
			$table->tinyInteger('kia_kipo')->default(0)->nullable();
			$table->tinyInteger('bangsamoro')->default(0)->nullable();
			$table->tinyInteger('lgu_sponsored')->default(0)->nullable();
			$table->tinyInteger('nga_sponsored')->default(0)->nullable();
			$table->tinyInteger('private_sponsored')->default(0)->nullable();
			$table->tinyInteger('pwd')->default(0)->nullable();
			$table->string('pwd_id_no')->nullable();
			$table->tinyInteger('point_of_service')->default(0)->nullable();
			$table->tinyInteger('financially_incapable')->default(0)->nullable();
			$table->tinyInteger('is_name_corrected')->default(0)->nullable();
			$table->text('name_corrected_from')->nullable();
			$table->text('name_corrected_to')->nullable();
			$table->tinyInteger('is_birthday_corrected')->default(0)->nullable();
			$table->date('birthday_corrected_from')->nullable();
			$table->date('birthday_corrected_to')->nullable();
			$table->tinyInteger('is_changed_civil_status')->default(0)->nullable();
			$table->text('changed_status_from')->nullable();
			$table->text('changed_status_to')->nullable();
			$table->tinyInteger('is_updated_info')->default(0)->nullable();
			$table->text('updated_info_from')->nullable();
			$table->text('updated_info_to')->nullable();
			$table->tinyInteger('is_thumbmark')->default(0)->nullable();
			$table->string('signature')->nullable();
			$table->date('date_signed')->nullable();
			$table->string('received_by')->nullable();
			$table->date('date_received')->nullable();
			$table->string('branch_received')->nullable();
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
        Schema::dropIfExists('patient_philhealth_details');
    }
};
