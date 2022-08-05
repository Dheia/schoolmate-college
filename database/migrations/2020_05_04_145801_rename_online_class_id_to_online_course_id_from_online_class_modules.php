<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameOnlineClassIdToOnlineCourseIdFromOnlineClassModules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('online_class_modules', function (Blueprint $table) {
            $table->renameColumn('online_class_id', 'online_course_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('online_class_modules', function (Blueprint $table) {
            $table->renameColumn('online_course_id', 'online_class_id');
        });
    }
}
