<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToPaynamicsPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('paynamics_payments', function (Blueprint $table) {
            $table->string('merchant_id')->nullable()->after('response_id');
            $table->string('expiry_limit')->nullable()->after('merchant_id');
            $table->longText('direct_otc_info')->nullable()->after('expiry_limit');
            $table->longText('payment_action_info')->nullable()->after('direct_otc_info');
            $table->longText('response')->nullable()->after('payment_action_info');
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
            $table->dropColumn('merchant_id');
            $table->dropColumn('expiry_limit');
            $table->dropColumn('direct_otc_info');
            $table->dropColumn('payment_action_info');
            $table->dropColumn('response');
        });
    }
}
