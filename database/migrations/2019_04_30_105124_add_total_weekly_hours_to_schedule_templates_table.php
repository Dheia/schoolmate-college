<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTotalWeeklyHoursToScheduleTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schedule_templates', function (Blueprint $table) {
            $table->integer('total_weekly_hours')->unsigned()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schedule_templates', function (Blueprint $table) {
            $table->dropColumn('total_weekly_hours');
        });
    }
}
