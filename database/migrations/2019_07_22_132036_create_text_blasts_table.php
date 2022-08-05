<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTextBlastsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('text_blasts', function (Blueprint $table) {
            $table->increments('id');
            $table->longText("subscribers");
            $table->longText("message");
            $table->integer("total");
            $table->longText("response");
            $table->datetime("send_date_time")->nullable();
            $table->boolean("is_now")->nullable();
            $table->boolean("success")->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('text_blasts');
    }
}
