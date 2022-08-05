<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSubjectDetailsToCurriculumManagementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('curriculum_managements', function (Blueprint $table) {
            $table->longText('subject_details')->after('curriculum_name');
        });

        Schema::table('section_managements', function (Blueprint $table) {
            $table->dropColumn('subject_details');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('curriculum_managements', function (Blueprint $table) {
            $table->dropColumn('subject_details');   
        });

        Schema::table('section_managements', function (Blueprint $table) {
            $table->longText('subject_details');
        });
    }
}
