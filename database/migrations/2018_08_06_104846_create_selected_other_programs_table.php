<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSelectedOtherProgramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('selected_other_programs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('other_program_id');
            $table->integer('student_no');
            $table->integer('payment_id');
            $table->integer('grade_level_id');
            $table->integer('school_year_id');
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
        Schema::dropIfExists('selected_other_programs');
    }
}
