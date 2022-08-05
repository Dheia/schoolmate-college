<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenamePaymentTypeIdToCommitmentPaymentIdFromPaymentHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_histories', function(Blueprint $table) {
            $table->renameColumn('payment_type_id', 'commitment_payment_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_histories', function(Blueprint $table) {
            $table->renameColumn('commitment_payment_id', 'payment_type_id');
        });
    }
}
