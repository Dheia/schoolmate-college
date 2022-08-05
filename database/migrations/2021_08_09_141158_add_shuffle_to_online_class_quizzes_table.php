<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShuffleToOnlineClassQuizzesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('online_class_quizzes', function (Blueprint $table) {
            $table->boolean('shuffle')->default(0)->after('allow_retake');
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
            $table->dropColumn('shuffle');
        });
    }
}
