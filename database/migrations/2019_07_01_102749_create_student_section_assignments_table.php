<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentSectionAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_section_assignments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('class_code');
            $table->integer('school_year_id');
            $table->integer('section_id');
            $table->integer('level_id');
            $table->longText('students');
            $table->softDeletes();
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
        Schema::dropIfExists('student_section_assignments');
    }
}
