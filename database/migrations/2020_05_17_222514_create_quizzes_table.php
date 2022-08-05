<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuizzesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->longText('description');
            $table->longText('questions')->nullable();
            $table->integer('points')->nullable();
            $table->integer('subject_id');
            $table->integer('teacher_id');
            $table->integer('user_id')->nullable();
            $table->integer('school_year_id');
            $table->boolean('active')->default(1);
            $table->boolean('archive')->default(0);
            $table->longText('json')->nullable();
            $table->datetime('start_at')->nullable();
            $table->datetime('end_at')->nullable();
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
        Schema::dropIfExists('quizzes');
    }
}
