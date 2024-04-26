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
            $table->string('prefix',50)->nullable()->default("");
            $table->string('country',10)->nullable()->default("ph");
            $table->string('region',50)->nullable()->default("ph");
            $table->string('province',100)->nullable()->default("");
            $table->string('landline',50)->nullable()->default("");
            $table->string('education_attainment',100)->nullable()->default("");
            $table->string('employment_status',100)->nullable()->default("");
            $table->string('mother_firstname',100)->nullable()->default("");
            $table->string('mother_lastname',100)->nullable()->default("");
            $table->string('mother_middlename',100)->nullable()->default("");
            $table->boolean('indigenous')->nullable()->default(false);
            $table->date('mother_birthdate')->nullable()->default(null);

            $table->string('family_member',100)->nullable()->default("");
            $table->boolean('dswd_nhts')->nullable()->default(false);
            $table->string('family_serial_no',100)->nullable()->default("");
            $table->boolean('phil_health_member')->nullable()->default(false);
            $table->string('phil_health_status_type',50)->nullable()->default("");
            $table->string('phil_health_category_type',50)->nullable()->default("");
            $table->boolean('pcb_eligble')->nullable()->default(false);
            $table->date('enlistment_date')->nullable()->default(null);





        

            


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
            $table->dropColumn(['prefix','education_attainment']);
        });
    }
};
