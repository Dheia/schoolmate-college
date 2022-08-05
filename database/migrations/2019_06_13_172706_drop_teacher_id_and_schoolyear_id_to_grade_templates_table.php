<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropTeacherIdAndSchoolyearIdToGradeTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('grade_templates', function (Blueprint $table) {
            $table->dropColumn("teacher_id");
            $table->dropColumn("schoolyear_id");
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
            $table->integer("teacher_id")->after("period_id")->nullable();
            $table->integer("schoolyear_id")->after("id")->nullable();
        });
    }
}
