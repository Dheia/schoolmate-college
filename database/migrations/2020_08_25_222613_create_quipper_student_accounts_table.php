<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuipperStudentAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quipper_student_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('membership_number');
            $table->string('user_id')->nullable();
            $table->string('username');
            $table->string('password');
            $table->string('email');
            $table->integer('student_id');
            // $table->integer('student_id')->unsigned();
            // $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quipper_student_accounts');
    }
}
