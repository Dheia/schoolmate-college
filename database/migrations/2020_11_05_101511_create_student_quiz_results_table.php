<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentQuizResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_quiz_results', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('studentnumber')->unsigned();
            $table->integer('online_class_quiz_id')->unsigned();
            $table->integer('attempts')->unsigned();
            $table->longText('results')->nullable();
            $table->integer('score')->nullable();
            $table->time('time_start_at');
            $table->time('time_end_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_quiz_results');
    }
}
