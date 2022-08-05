<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropSubjectDetailsToCurriculumManagementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('curriculum_managements', function (Blueprint $table) {
            $table->dropColumn("subject_details");
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
            $table->string("subject_details")->after("curriculum_name")->nullable();
        });
    }
}
