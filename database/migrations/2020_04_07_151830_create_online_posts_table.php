<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOnlinePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('online_posts', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('content')->nullable();
            $table->longText('files')->nullable();
            $table->integer('online_class_id');
            $table->integer('teacher_id')->nullable();
            $table->integer('studentnumber')->nullable();
            $table->integer('subject_id');
            $table->integer('section_id');
            $table->integer('school_year_id');
            $table->string('term_type')->nullable();
            $table->boolean('active')->default('1');
            $table->boolean('archive')->default('0');
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
        Schema::dropIfExists('online_posts');
    }
}
