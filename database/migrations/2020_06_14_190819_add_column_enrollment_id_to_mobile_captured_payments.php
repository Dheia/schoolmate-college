<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnEnrollmentIdToMobileCapturedPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mobile_captured_payments', function (Blueprint $table) {
            //
            $table->integer('enrollment_id')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mobile_captured_payments', function (Blueprint $table) {
            $table->dropColumn('enrollment_id');
        });
    }
}
