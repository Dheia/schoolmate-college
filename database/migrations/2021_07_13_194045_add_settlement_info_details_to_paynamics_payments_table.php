<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSettlementInfoDetailsToPaynamicsPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('paynamics_payments', function (Blueprint $table) {
            $table->longText('settlement_info_details')->nullable()->after('response_advise');
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
            $table->dropColumn('settlement_info_details');
        });
    }
}
