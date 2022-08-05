<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOnlineClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('online_classes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->uniqid();
            $table->string('name');
            $table->longText('description')->nullable();
            $table->integer('teacher_id');
            $table->integer('subject_id');
            $table->integer('section_id');
            $table->integer('school_year_id');
            $table->string('term_type')->nullable();
            $table->string('color')->default('#0000ff');
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
        Schema::dropIfExists('online_classes');
    }
}
