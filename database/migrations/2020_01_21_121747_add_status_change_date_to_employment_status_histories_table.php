<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusChangeDateToEmploymentStatusHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employment_status_histories', function (Blueprint $table) {
            $table->date('status_change_date')->after('employment_status_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employment_status_histories', function (Blueprint $table) {
            $table->dropColumn('status_change_date');
        });
    }
}
