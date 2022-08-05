<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApplyToSpecialDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('special_discounts', function (Blueprint $table) {
            $table->enum('apply_to', ['TuitionFeeOnly', 'TuitionFeeAndMiscFee'])->after('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('special_discounts', function (Blueprint $table) {
            $table->dropColumn('apply_to');
        });
    }
}
