<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyColumsOfOnlineClassQuizzesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('online_class_quizzes', function (Blueprint $table) {
            $table->datetime('start_at')->nullable()->change();
            $table->datetime('end_at')->nullable()->change();
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
            $table->datetime('start_at')->change();
            $table->datetime('end_at')->change();
        });
    }
}
