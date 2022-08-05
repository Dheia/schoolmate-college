<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEncodeGradeSchedules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('encode_grade_schedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('school_year_id');
            $table->unsignedInteger('department_id');
            $table->string('term_type');
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->timestamps();
            $table->softDeletes();
        });

        // Schema::table('encode_grade_schedules', function (Blueprint $table) {
        //     $table->foreign('school_year_id')
        //         ->references('id')
        //         ->on('school_years')
        //         ->onDelete('cascade');
        //     $table->foreign('department_id')
        //         ->references('id')
        //         ->on('departments')
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
        Schema::dropIfExists('encode_grade_schedules');
    }
}
