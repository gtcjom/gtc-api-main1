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
        Schema::create('patient_dependents', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('patient_id');
			$table->string('firstname');
			$table->string('lastname')->nullable();
			$table->string('middle_name')->nullable();
			$table->string('name_extension')->nullable();
			$table->string('relationship')->nullable();
			$table->date('birthday')->nullable();
			$table->string('citizenship')->nullable();
			$table->tinyInteger('is_permanently_disabled');
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
        Schema::dropIfExists('patient_dependents');
    }
};
