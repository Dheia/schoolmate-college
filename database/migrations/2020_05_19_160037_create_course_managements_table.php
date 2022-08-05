<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseManagementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_managements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('level_id');
            $table->string('acronym');
            $table->string('name');
            $table->string('major')->nullable();
            $table->string('minor')->nullable();
            $table->string('description');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_managements');
    }
}
