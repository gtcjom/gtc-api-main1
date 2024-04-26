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
        Schema::create('laboratory_results', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('laboratory_order_id');
			$table->string('remarks')->nullable();
			$table->string('image')->nullable();
			$table->string('status')->nullable();
			$table->unsignedBigInteger('added_by')->nullable();
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
        Schema::dropIfExists('laboratory_results');
    }
};
