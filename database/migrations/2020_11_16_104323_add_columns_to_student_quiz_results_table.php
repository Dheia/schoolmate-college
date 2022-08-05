<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToStudentQuizResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_quiz_results', function (Blueprint $table) {
            $table->longText('questionnaire')->nullable()->after('attempts');
            $table->boolean('is_check')->default(0)->after('score');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_quiz_results', function (Blueprint $table) {
            $table->dropColumn('questionnaire');
            $table->dropColumn('is_check');
        });
    }
}
