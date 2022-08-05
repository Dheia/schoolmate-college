<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeAttendanceRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_attendance_rules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('rule_name');
            $table->integer('school_year_id');
            $table->integer('applied_in_pretime');
            $table->integer('applied_in_posttime');
            $table->boolean('allowed_overtime');
            $table->bigInteger('total_working_hours_week');

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
        Schema::dropIfExists('employee_attendance_rules');
    }
}
