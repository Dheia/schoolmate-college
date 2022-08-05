<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubjectManagementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subject_managements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('curriculum_id');
            // $table->string('curriculum');
            $table->string('subject_code');
            $table->string('subject_description');
            $table->double('no_unit');
            $table->double('price')->nullable();
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
        Schema::dropIfExists('subject_managements');
    }
}
