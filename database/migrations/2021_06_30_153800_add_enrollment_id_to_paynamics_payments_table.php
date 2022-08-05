<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEnrollmentIdToPaynamicsPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('paynamics_payments', function (Blueprint $table) {
            $table->integer('enrollment_id')->unsigned()->after('studentnumber');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('paynamics_payments', function (Blueprint $table) {
            $table->dropColumn('enrollment_id');
        });
    }
}
