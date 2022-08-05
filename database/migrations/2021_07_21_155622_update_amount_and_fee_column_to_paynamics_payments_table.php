<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAmountAndFeeColumnToPaynamicsPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('paynamics_payments', function (Blueprint $table) {
            $table->decimal('amount', $precision = 9, $scale = 2)->change();
            $table->decimal('fee', $precision = 9, $scale = 2)->change();
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
            $table->integer('amount')->change();
            $table->integer('fee')->change();
        });
    }
}
