<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEncodeGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('encode_grades', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('template_id');
            $table->integer('subject_id');
            // $table->integer('school_year_id');
            $table->integer('section_id');
            $table->integer('teacher_id');
            $table->longText('state')->nullable();
            $table->longText('rows')->nullable();
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
        Schema::dropIfExists('encode_grades');
    }
}
