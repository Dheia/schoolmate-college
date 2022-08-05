<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddZoomIdAndStatusToOnlineClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('online_classes', function (Blueprint $table) {
            $table->string('zoom_id')->after('color')->nullable();
            $table->string('status')->after('zoom_id')->nullable();
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
            $table->dropColumn('zoom_id');
            $table->dropColumn('status');
        });
    }
}
