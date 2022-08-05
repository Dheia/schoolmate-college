<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTemplateIdSectionIdToSetupGrades extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('setup_grades', function (Blueprint $table) {
            $table->integer('template_id')->nullable()->after('id');
            $table->integer('section_id')->nullable()->after('subject_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('setup_grades', function (Blueprint $table) {
            $table->dropColumn('template_id');
            $table->dropColumn('section_id');
        });
    }
}
