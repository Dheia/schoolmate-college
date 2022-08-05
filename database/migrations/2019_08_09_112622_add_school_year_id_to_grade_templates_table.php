<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSchoolYearIdToGradeTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('grade_templates', function (Blueprint $table) {
            $table->integer('school_year_id')->after('name');
            $table->dropColumn('period_id');
            $table->dropColumn('department_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('grade_templates', function (Blueprint $table) {
            $table->dropColumn('school_year_id');
            $table->integer('department_id')->after('id');
            $table->integer('period_id')->after('department_id');
        });
    }
}
