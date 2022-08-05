<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnrollmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('studentnumber');
            $table->integer('tuition_id')->unsigned();
            $table->integer('school_year_id')->unsigned();
            $table->integer('level_id')->unsigned();
            $table->integer('curriculum_id')->unsigned();
            $table->integer('section_id')->unsigned();
            $table->integer('commitment_payment_id')->unsigned();
            $table->boolean('is_passed')->nullable();
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
        Schema::dropIfExists('enrollments');
    }
}
