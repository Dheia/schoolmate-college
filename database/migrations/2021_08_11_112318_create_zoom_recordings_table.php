<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZoomRecordingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zoom_recordings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('meetingable_id')->unsigned();
            $table->string('meetingable_type');
            $table->string('zoom_id')->nullable();
            $table->string('zoom_uuid')->nullable();
            $table->string('zoom_host_id')->nullable();
            $table->string('duration')->nullable();
            $table->longText('share_url')->nullable();
            $table->longText('recording_files')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('zoom_recordings');
    }
}
