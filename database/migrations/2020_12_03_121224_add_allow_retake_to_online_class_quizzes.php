<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAllowRetakeToOnlineClassQuizzes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('online_class_quizzes', function (Blueprint $table) {
            $table->boolean('allow_retake')->default(0)->after('allow_late_submission');
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
            $table->dropColumn('allow_retake');
        });
    }
}
