<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOnlineTopicPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('online_topic_pages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('online_class_id');
            $table->integer('online_class_topic_id');
            $table->string('title');
            $table->longText('description')->nullable();
            $table->string('type');
            $table->longText('files')->nullable();
            $table->longText('video')->nullable();
            $table->integer('quiz_id')->nullable();
            $table->boolean('active')->default('1');
            $table->boolean('archive')->default('0');
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
        Schema::dropIfExists('online_topic_pages');
    }
}
