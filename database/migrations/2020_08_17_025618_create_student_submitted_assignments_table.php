<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentSubmittedAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_submitted_assignments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('assignment_id');
            $table->integer('student_id');
            $table->longText('answer')->nullable();
            $table->longText('file')->nullable();
            $table->string('status');
            $table->longText('rubrics');
            $table->integer('score')->nullable();
            $table->boolean('archive')->default('0');
            $table->boolean('active')->default('1');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_submitted_assignments');
    }
}
