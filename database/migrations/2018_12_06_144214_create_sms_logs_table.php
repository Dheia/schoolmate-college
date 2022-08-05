<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('studentnumber')->nullable();
            $table->boolean('is_sent_entrance')->nullable();
            $table->boolean('is_sent_exit')->nullable();
            $table->longText('delivery_report_entrance')->nullable();
            $table->longText('delivery_report_exit')->nullable();
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
        Schema::dropIfExists('sms_logs');
    }
}
