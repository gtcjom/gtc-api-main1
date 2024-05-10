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
        Schema::create('inventory_csrs', function (Blueprint $table) {
            $table->id();
            $table->date('csr_date')->nullable();
            $table->string('csr_supplies')->nullable();
            $table->string('csr_quantity')->nullable();
            $table->string('csr_status')->nullable();
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
        Schema::dropIfExists('inventory_csrs');
    }
};
