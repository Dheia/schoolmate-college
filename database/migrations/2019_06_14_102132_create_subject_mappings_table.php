<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubjectMappingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subject_mappings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('curriculum_id')->unsigned();
            $table->integer('department_id')->unsigned();
            $table->integer('level_id')->unsigned();
            $table->integer('term_id')->unsigned();
            $table->integer('track_id')->unsigned()->nullable();
            $table->longText('subjects');
            $table->timestamps();

            $table->foreign('curriculum_id')->references('id')->on('curriculum_managements');
            $table->foreign('department_id')->references('id')->on('departments');
            $table->foreign('level_id')->references('id')->on('year_managements');
            $table->foreign('term_id')->references('id')->on('term_managements');
            $table->foreign('track_id')->references('id')->on('track_managements')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subject_mappings');
    }
}
