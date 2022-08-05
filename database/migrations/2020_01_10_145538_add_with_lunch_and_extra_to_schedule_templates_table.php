<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWithLunchAndExtraToScheduleTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schedule_templates', function (Blueprint $table) {
            $table->integer('lunch_break_minutes_mon')->default(0)->unsigned()->nullable()->after('mon_timeout');
            $table->boolean('rest_day_mon')->default(0)->after('lunch_break_minutes_mon');
            $table->integer('no_of_hours_mon')->nullable()->after('rest_day_mon');    
            $table->integer('lunch_break_minutes_tue')->default(0)->unsigned()->nullable()->after('tue_timeout');
            $table->boolean('rest_day_tue')->default(0)->after('lunch_break_minutes_tue');
            $table->integer('no_of_hours_tue')->nullable()->after('rest_day_tue');    
            $table->integer('lunch_break_minutes_wed')->default(0)->unsigned()->nullable()->after('wed_timeout');
            $table->boolean('rest_day_wed')->default(0)->after('lunch_break_minutes_wed');
            $table->integer('no_of_hours_wed')->nullable()->after('rest_day_wed');    
            $table->integer('lunch_break_minutes_thu')->default(0)->unsigned()->nullable()->after('thu_timeout');
            $table->boolean('rest_day_thu')->default(0)->after('lunch_break_minutes_thu');
            $table->integer('no_of_hours_thu')->nullable()->after('rest_day_thu');    
            $table->integer('lunch_break_minutes_fri')->default(0)->unsigned()->nullable()->after('fri_timeout');
            $table->boolean('rest_day_fri')->default(0)->after('lunch_break_minutes_fri');
            $table->integer('no_of_hours_fri')->nullable()->after('rest_day_fri');    
            $table->integer('lunch_break_minutes_sat')->default(0)->unsigned()->nullable()->after('sat_timeout');
            $table->boolean('rest_day_sat')->default(0)->after('lunch_break_minutes_sat');
            $table->integer('no_of_hours_sat')->nullable()->after('rest_day_sat');    
            $table->integer('lunch_break_minutes_sun')->default(0)->unsigned()->nullable()->after('sun_timeout');
            $table->boolean('rest_day_sun')->default(0)->after('lunch_break_minutes_sun');
            $table->integer('no_of_hours_sun')->nullable()->after('rest_day_sun');             
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
            $table->dropColumn('lunch_break_minutes_mon');
            $table->dropColumn('rest_day_mon');
            $table->dropColumn('no_of_hours_mon');
            $table->dropColumn('lunch_break_minutes_tue');
            $table->dropColumn('rest_day_tue');
            $table->dropColumn('no_of_hours_tue');
            $table->dropColumn('lunch_break_minutes_wed');
            $table->dropColumn('rest_day_wed');
            $table->dropColumn('no_of_hours_wed');
            $table->dropColumn('lunch_break_minutes_thu');
            $table->dropColumn('rest_day_thu');
            $table->dropColumn('no_of_hours_thu');
            $table->dropColumn('lunch_break_minutes_fri');
            $table->dropColumn('rest_day_fri');
            $table->dropColumn('no_of_hours_fri');
            $table->dropColumn('lunch_break_minutes_sat');
            $table->dropColumn('rest_day_sat');
            $table->dropColumn('no_of_hours_sat');
            $table->dropColumn('lunch_break_minutes_sun');
            $table->dropColumn('rest_day_sun');
            $table->dropColumn('no_of_hours_sun');
        });
    }
}
