<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKioskEnrollmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kiosk_enrollments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kiosk_id');
            $table->integer('student_id')->unsigned()->nullable();
            $table->integer('enrollment_id')->unsigned()->nullable();
            $table->string('email')->nullable();
            $table->enum('student_status', ['old', 'new']);
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
        Schema::dropIfExists('kiosk_enrollments');
    }
}
