<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropOnlineClassIdToOnlineTopicPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('online_topic_pages', function (Blueprint $table) {
            $table->dropColumn('online_class_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('online_topic_pages', function (Blueprint $table) {
            $table->integer('online_class_id')->after('id');
        });
    }
}
