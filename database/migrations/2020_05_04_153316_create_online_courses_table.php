<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOnlineCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('online_courses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->uniqid();
            $table->string('name');
            $table->longText('description')->nullable();
            $table->longText('requirements')->nullable();
            $table->string('duration')->nullable();
            $table->integer('teacher_id');
            $table->integer('subject_id')->nullable();
            $table->integer('level_id')->nullable();
            $table->string('color')->default('#3c8dbc');
            $table->boolean('active')->default('1');
            $table->boolean('archive')->default('0');
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
        Schema::dropIfExists('online_courses');
    }
}
