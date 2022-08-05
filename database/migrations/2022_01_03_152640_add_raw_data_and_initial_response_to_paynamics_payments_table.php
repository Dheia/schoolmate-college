<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRawDataAndInitialResponseToPaynamicsPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('paynamics_payments', function (Blueprint $table) {
            $table->longText('raw_data')->nullable()->after('fee');
            $table->longText('initial_response')->nullable()->after('raw_data');

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
            $table->dropColumn('raw_data');
            $table->dropColumn('initial_response');
        });
    }
}
