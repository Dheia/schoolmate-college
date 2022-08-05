<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGradeTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grade_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('schoolyear_id')->nullable();
            $table->integer('department_id')->nullable();
            $table->integer('period_id')->nullable();
            $table->integer('teacher_id')->nullable();
            $table->string('name')->unique();
            $table->string('description')->nullable();
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
        Schema::dropIfExists('grade_templates');
    }
}
