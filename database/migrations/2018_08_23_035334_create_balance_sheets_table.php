<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBalanceSheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('balance_sheets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('code')->nullable();
            $table->enum('hierarchy_type', ['Group', 'Account']);
            $table->string('group_id');
            $table->enum('tax_code', ['VAT 0%'])->nullable();
            $table->boolean('is_control_account')->nullable();
            $table->integer('made_up_id')->nullable();
            $table->boolean('is_starting_balance')->nullable();
            $table->enum('starting_balance_type_id', ['Debit', 'Credit'])->nullable();
            $table->string('starting_balance')->default(0);
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
        Schema::dropIfExists('balance_sheets');
    }
}
