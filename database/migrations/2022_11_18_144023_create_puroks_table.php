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
        Schema::create('puroks', function (Blueprint $table) {
            $table->id();
            $table->float('number',8,1,true);
            $table->string('name');
            $table->string('alias')->nullable();
            $table->double('lat')->nullable();
            $table->double('lng')->nullable();
            $table->foreignId('barangay_id');
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
        Schema::dropIfExists('puroks');
    }
};
