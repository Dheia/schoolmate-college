<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScheduleTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->time('mon_timein')->nullable();
            $table->time('mon_timeout')->nullable();

            $table->time('tue_timein')->nullable();
            $table->time('tue_timeout')->nullable();

            $table->time('wed_timein')->nullable();
            $table->time('wed_timeout')->nullable();

            $table->time('thu_timein')->nullable();
            $table->time('thu_timeout')->nullable();

            $table->time('fri_timein')->nullable();
            $table->time('fri_timeout')->nullable();

            $table->time('sat_timein')->nullable();
            $table->time('sat_timeout')->nullable();

            $table->time('sun_timein')->nullable();
            $table->time('sun_timeout')->nullable();

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
        Schema::dropIfExists('schedule_templates');
    }
}
