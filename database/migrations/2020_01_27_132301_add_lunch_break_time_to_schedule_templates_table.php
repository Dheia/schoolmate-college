<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLunchBreakTimeToScheduleTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schedule_templates', function (Blueprint $table) {
            $table->time('lunch_break_time_start_mon')->nullable()->after('mon_timein');
            $table->time('lunch_break_time_start_tue')->nullable()->after('tue_timein');
            $table->time('lunch_break_time_start_wed')->nullable()->after('wed_timein');
            $table->time('lunch_break_time_start_thu')->nullable()->after('thu_timein');
            $table->time('lunch_break_time_start_fri')->nullable()->after('fri_timein');
            $table->time('lunch_break_time_start_sat')->nullable()->after('sat_timein');
            $table->time('lunch_break_time_start_sun')->nullable()->after('sun_timein');

            $table->time('lunch_break_time_end_mon')->nullable()->after('lunch_break_time_start_mon');
            $table->time('lunch_break_time_end_tue')->nullable()->after('lunch_break_time_start_tue');
            $table->time('lunch_break_time_end_wed')->nullable()->after('lunch_break_time_start_wed');
            $table->time('lunch_break_time_end_thu')->nullable()->after('lunch_break_time_start_thu');
            $table->time('lunch_break_time_end_fri')->nullable()->after('lunch_break_time_start_fri');
            $table->time('lunch_break_time_end_sat')->nullable()->after('lunch_break_time_start_sat');
            $table->time('lunch_break_time_end_sun')->nullable()->after('lunch_break_time_start_sun');
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
            $table->dropColumn('lunch_break_time_start_mon');
            $table->dropColumn('lunch_break_time_start_tue');
            $table->dropColumn('lunch_break_time_start_wed');
            $table->dropColumn('lunch_break_time_start_thu');
            $table->dropColumn('lunch_break_time_start_fri');
            $table->dropColumn('lunch_break_time_start_sat');
            $table->dropColumn('lunch_break_time_start_sun');

            $table->dropColumn('lunch_break_time_end_mon');
            $table->dropColumn('lunch_break_time_end_tue');
            $table->dropColumn('lunch_break_time_end_wed');
            $table->dropColumn('lunch_break_time_end_thu');
            $table->dropColumn('lunch_break_time_end_fri');
            $table->dropColumn('lunch_break_time_end_sat');
            $table->dropColumn('lunch_break_time_end_sun');
        });
    }
}
