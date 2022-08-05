<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddScheduleToOnlineClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('online_classes', function (Blueprint $table) {
            $table->string('days')->after('description')->nullable();
            $table->time('start_time')->after('days')->nullable();
            $table->time('end_time')->after('start_time')->nullable();
            $table->boolean('link_to_quipper')->after('end_time')->default('0');
            $table->longText('substitute_teachers')->after('link_to_quipper')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('online_classes', function (Blueprint $table) {
            $table->dropColumn('days');
            $table->dropColumn('start_time');
            $table->dropColumn('end_time');
            $table->dropColumn('link_to_quipper');
            $table->dropColumn('substitute_teachers');
        });
    }
}
