<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCashAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('code');
            $table->decimal('credit_limit', 20, 2)->nullable();
            $table->integer('currency_id');
            $table->string('currency');
            $table->boolean('is_bank_maintained')->default(False);
            $table->boolean('is_starting_balance')->default(False);
            $table->decimal('starting_balance', 20, 2)->nullable();
            $table->boolean('inactive')->default(False);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cash_accounts', 'cash_account');
    }
}
