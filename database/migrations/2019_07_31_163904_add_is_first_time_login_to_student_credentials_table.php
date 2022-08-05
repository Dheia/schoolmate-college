<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsFirstTimeLoginToStudentCredentialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_credentials', function (Blueprint $table) {
            $table->boolean('is_first_time_login')->after('password')->default(true);
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
            $table->dropColumn('is_first_time_login');
        });
    }
}
