<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropLevelIdToStudentSectionAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_section_assignments', function (Blueprint $table) {
            $table->dropColumn('level_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_section_assignments', function (Blueprint $table) {
            $table->integer('level_id')->after('section_id')->nullable();
        });
    }
}
