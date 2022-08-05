<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTurnstileLocationAtTurnstileLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('turnstile_logs', function (Blueprint $table) {
            //
            $table->longText('location_in')->after('timeout')->nullable();
            $table->longText('location_out')->after('timeout')->nullable();
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('turnstile_logs', function (Blueprint $table) {
            //
            $table->dropColumn('location_in');
            $table->dropColumn('location_out');
           
        });
    }
}
