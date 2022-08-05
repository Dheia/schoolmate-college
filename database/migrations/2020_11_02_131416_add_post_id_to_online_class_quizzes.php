<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPostIdToOnlineClassQuizzes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('online_class_quizzes', function (Blueprint $table) {
            $table->integer('online_post_id')->after('quiz_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('online_class_quizzes', function (Blueprint $table) {
            $table->dropColumn('online_post_id');
        });
    }
}
