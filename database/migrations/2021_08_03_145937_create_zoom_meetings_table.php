<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZoomMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zoom_meetings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('meetingable_id')->unsigned();
            $table->string('meetingable_type');
            $table->integer('zoom_user_id')->nullable();
            $table->bigInteger('employee_id')->unsigned();
            $table->string('zoom_uuid')->nullable();
            $table->string('zoom_id')->nullable();
            $table->string('zoom_host_id')->nullable();
            $table->longText('data')->nullable();
            $table->string('status');
            $table->boolean('active');
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
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
        Schema::dropIfExists('zoom_meetings');
    }
}
