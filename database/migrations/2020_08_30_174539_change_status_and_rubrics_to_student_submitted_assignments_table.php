<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeStatusAndRubricsToStudentSubmittedAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_submitted_assignments', function (Blueprint $table) {
            $table->string('status')->nullable()->change();
            $table->longText('rubrics')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_submitted_assignments', function (Blueprint $table) {
            $table->string('status')->change();
            $table->longText('rubrics')->change();
        });
    }
}
