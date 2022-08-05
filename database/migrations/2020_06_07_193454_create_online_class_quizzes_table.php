<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOnlineClassQuizzesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('online_class_quizzes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('online_class_id');
            $table->integer('quiz_id');
            $table->time('max_time')->nullable();
            $table->datetime('start_at');
            $table->datetime('end_at');
            $table->integer('school_year_id');
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
        Schema::dropIfExists('online_class_quizzes');
    }
}
