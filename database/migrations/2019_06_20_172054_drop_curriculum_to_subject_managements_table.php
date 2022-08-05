<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropCurriculumToSubjectManagementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subject_managements', function (Blueprint $table) {
            $table->dropColumn('curriculum_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subject_managements', function (Blueprint $table) {
            $table->integer('curriculum_id')->after('id')->nullable();
        });
    }
}
