<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeEncodeGradeSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('employee_encode_grade_schedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('employee_id');
            $table->unsignedInteger('school_year_id');
            $table->string('term_type');
            $table->unsignedInteger('section_id');
            $table->unsignedInteger('subject_id');
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->timestamps();
            $table->softDeletes();
        });

        // Schema::table('employee_encode_grade_schedules', function (Blueprint $table) {
        //     $table->foreign('school_year_id')
        //         ->references('id')
        //         ->on('school_years')
        //         ->onDelete('cascade');
        //     $table->foreign('employee_id')
        //         ->references('id')
        //         ->on('employees')
        //         ->onDelete('cascade');
        //     $table->foreign('section_id')
        //         ->references('id')
        //         ->on('section_managements')
        //         ->onDelete('cascade');
        //     $table->foreign('subject_id')
        //         ->references('id')
        //         ->on('subject_managements')
        //         ->onDelete('cascade');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_encode_grade_schedules');
    }
}
