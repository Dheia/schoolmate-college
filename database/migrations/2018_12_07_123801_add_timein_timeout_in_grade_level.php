<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimeinTimeoutInGradeLevel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('year_managements', function (Blueprint $table) {
            $table->time('time_in')->nullable()->after('year');
            $table->time('time_out')->nullable()->after('time_in');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('year_managements', function (Blueprint $table) {
            $table->dropColumn('time_in');
            $table->dropColumn('time_out');
        });
    }
}
