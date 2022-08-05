<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToBookTransanctionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('book_transactions', function (Blueprint $table) {
            $table->integer('fine')->nullable()->after('date_returned');
            $table->integer('paid')->nullable()->after('fine');
            $table->datetime('paid_date')->nullable()->after('paid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('book_transactions', function (Blueprint $table) {
            $table->dropColumn('fine');
            $table->dropColumn('paid');
            $table->dropColumn('paid_date');
        });
    }
}
