<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContentStandardToOnlineCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('online_courses', function (Blueprint $table) {
            $table->longText('content_standard')->after('requirements')->nullable();
            $table->longText('performance_standard')->after('content_standard')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('online_courses', function (Blueprint $table) {
            $table->dropColumn('content_standard');
            $table->dropColumn('performance_standard');
        });
    }
}
