<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenamePaymentIdToCommitmentPaymentIdToSelectedOtherProgramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('selected_other_programs', function (Blueprint $table) {
            $table->renameColumn('payment_id', 'commitment_payment_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('selected_other_programs', function (Blueprint $table) {
            $table->renameColumn('commitment_payment_id', 'payment_id');
        });
    }
}
