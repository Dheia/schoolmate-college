<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserTypeAndPositionToStudentSmsTaggingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_sms_taggings', function (Blueprint $table) {
            $table->enum('user_type', ['student', 'employee'])->after('studentnumber');
            $table->string('position_type')->after('user_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_sms_taggings', function (Blueprint $table) {
            $table->dropColumn('user_type');
            $table->dropColumn('position_type');
        });
    }
}
