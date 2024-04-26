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
        Schema::create('imagings', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('patient_id');
			$table->string('description')->nullable();
			$table->string('type')->nullable();
			$table->string('image')->nullable();
			$table->string('requested_by')->nullable();
			$table->datetime('requested_at')->nullable();
			$table->string('processed_by')->nullable();
			$table->datetime('processed_at')->nullable();
			$table->string('status')->nullable();
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
        Schema::dropIfExists('imagings');
    }
};
