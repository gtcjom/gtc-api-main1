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
        Schema::table('appointment_data', function (Blueprint $table) {
            //
            $table->string('specimen_microbiology', 10)->default(null)->nullable();
            $table->string('source_microbiology', 10)->default(null)->nullable();
            $table->string('result_microbiology', 10)->default(null)->nullable();
            $table->string('culture_isolate', 10)->default(null)->nullable();
            $table->string('sensitive', 10)->default(null)->nullable();
            $table->string('resistant', 10)->default(null)->nullable();
            $table->string('intermediate', 10)->default(null)->nullable();

            $table->string('specimen_gram', 10)->default(null)->nullable();
            $table->string('result_gram', 10)->default(null)->nullable();
            $table->string('epithelial_cells', 10)->default(null)->nullable();
            $table->string('polymorphonuclears', 10)->default(null)->nullable();
            $table->string('remarks_gram', 10)->default(null)->nullable();

            $table->string('specimen_afb', 10)->default(null)->nullable();
            $table->string('result_afb', 10)->default(null)->nullable();
            $table->string('remarks_afb', 10)->default(null)->nullable();

            $table->string('specimen_koh', 10)->default(null)->nullable();
            $table->string('result_koh', 10)->default(null)->nullable();
            $table->string('remarks_koh', 10)->default(null)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appointment_data', function (Blueprint $table) {
            //
            $table->dropColumn('specimen_microbiology');
            $table->dropColumn('source_microbiology');
            $table->dropColumn('result_microbiology');
            $table->dropColumn('culture_isolate');
            $table->dropColumn('sensitive');
            $table->dropColumn('resistant');
            $table->dropColumn('intermediate');

            $table->dropColumn('specimen_gram');
            $table->dropColumn('result_gram');
            $table->dropColumn('epithelial_cells');
            $table->dropColumn('polymorphonuclears');
            $table->dropColumn('remarks_gram');

            $table->dropColumn('specimen_afb');
            $table->dropColumn('result_afb');
            $table->dropColumn('remarks_afb');

            $table->dropColumn('specimen_koh');
            $table->dropColumn('result_koh');
            $table->dropColumn('remarks_koh');
        });
    }
};
