<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnsToBookTransanctionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('book_transactions', function (Blueprint $table) {
            $table->dropColumn(['date_borrowed', 'due_date']);
        });
        Schema::table('book_transactions', function (Blueprint $table) {
            $table->datetime('date_borrowed')->nullable()->after('employee_id');
            $table->datetime('due_date')->nullable()->after('date_borrowed');
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
            $table->dropColumn(['date_borrowed', 'due_date']);
        });
        Schema::table('book_transactions', function (Blueprint $table) {
            $table->datetime('date_borrowed')->nullable()->after('employee_id');
            $table->datetime('due_date')->nullable()->after('date_borrowed');
        });
    }
}
