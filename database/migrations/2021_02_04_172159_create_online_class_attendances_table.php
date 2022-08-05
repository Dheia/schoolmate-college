<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOnlineClassAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('online_class_attendances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('online_class_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->string('user_type');
            $table->time('time_in', 0)->nullable();
            $table->time('time_out', 0)->nullable();
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
        Schema::dropIfExists('online_class_attendances');
    }
}
