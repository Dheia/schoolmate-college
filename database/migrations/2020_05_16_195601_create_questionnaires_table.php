<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionnairesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questionnaires', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('subject_id');
            $table->integer('teacher_id');
            $table->integer('user_id')->nullable();
            $table->integer('school_year_id');
            $table->string('type');
            $table->longText('description')->nullable();
            $table->longText('question');
            $table->longText('attachments');
            $table->longText('choices')->nullable();
            $table->longText('json')->nullable();
            $table->longText('answer')->nullable();
            $table->integer('points')->default(1);
            $table->boolean('active')->default(1);
            $table->boolean('archive')->default(0);
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
        Schema::dropIfExists('questionnaires');
    }
}
