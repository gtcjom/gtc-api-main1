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
            $table->string('spouse_last_name')->nullable();
            $table->string('spouse_first_name')->nullable();
            $table->string('spouse_name_ext')->nullable();
            $table->string('spouse_middle_name')->nullable();
			$table->string('address_block')->nullable();
            $table->string('philsys_id_no')->nullable();
            $table->string('tax_payer_id_no')->nullable();
            $table->string('building_name')->nullable();
            $table->string('subdivision')->nullable();
            $table->tinyInteger('is_mail_address')->nullable();
            $table->text('mail_building_name')->nullable();
            $table->text('mail_address_block')->nullable();
            $table->text('mail_street')->nullable();
            $table->text('mail_subdivision')->nullable();
            $table->text('mail_barangay')->nullable();
            $table->text('mail_city')->nullable();
            $table->text('mail_province')->nullable();
            $table->text('mail_zip_code')->nullable();
            $table->text('business_direct_line')->nullable();

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
            $table->dropColumn([
				'spouse_last_name',
				'spouse_first_name',
				'spouse_name_ext',
				'spouse_middle_name',
				'address_block',
				'philsys_id_no',
				'tax_payer_id_no',
				'building_name',
				'subdivision',
				'is_mail_address',
				'mail_building_name',
				'mail_address_block',
				'mail_street',
				'mail_subdivision',
				'mail_barangay',
				'mail_city',
				'mail_province',
				'mail_zip_code',
				'business_direct_line'
			]);
        });
    }
};
