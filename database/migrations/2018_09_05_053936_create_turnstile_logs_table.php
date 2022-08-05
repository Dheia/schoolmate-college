<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTurnstileLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('turnstile_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('rfid');
            $table->time('timein');
            $table->time('timeout')->nullable();
            $table->boolean('is_logged_in')->nullable();
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
        Schema::dropIfExists('turnstile_logs');
    }
}
