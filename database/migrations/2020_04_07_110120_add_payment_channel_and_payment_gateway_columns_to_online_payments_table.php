<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentChannelAndPaymentGatewayColumnsToOnlinePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('online_payments', function (Blueprint $table) {
            $table->string('payment_channel')->after('ip_address')->default('web');
            $table->string('payment_gateway')->after('payment_channel');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('online_payments', function (Blueprint $table) {
            $table->dropColumn('payment_channel');
            $table->dropColumn('payment_gateway');
        });
    }
}
