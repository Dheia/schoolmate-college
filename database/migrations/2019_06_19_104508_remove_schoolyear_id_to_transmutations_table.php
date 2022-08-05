<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveSchoolyearIdToTransmutationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transmutations', function (Blueprint $table) {
            $table->dropColumn('school_year_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transmutations', function (Blueprint $table) {
            $table->integer('schoolyear_id')->after('name');
        });
    }
}
