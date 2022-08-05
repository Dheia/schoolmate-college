<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsDisabledToStudentCredentialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_credentials', function (Blueprint $table) {
            $table->boolean('is_disabled')->after('is_first_time_login')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_credentials', function (Blueprint $table) {
            $table->dropColumn('is_disabled');
        });
    }
}
