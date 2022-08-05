<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddActiveToCommitmentPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('commitment_payments', function (Blueprint $table) {
            $table->integer('sequence')->unsigned()->after('additional_fee');
            $table->boolean('active')->default(0)->after('sequence');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('commitment_payments', function (Blueprint $table) {
            $table->dropColumn('active');
            $table->dropColumn('sequence');
        });
    }
}
