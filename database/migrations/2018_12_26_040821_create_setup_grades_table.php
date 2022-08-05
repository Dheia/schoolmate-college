<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSetupGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setup_grades', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('subject_id')->nullable();
            $table->integer('teacher_id')->nullable();
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('max')->nullable();
            $table->enum('type',['percent','raw'])->nullable();
            $table->integer('parent_id')->nullable();
            $table->integer('lft')->nullable();
            $table->integer('rgt')->nullable();
            $table->integer('depth')->nullable();
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
        Schema::dropIfExists('setup_grades');
    }
}
