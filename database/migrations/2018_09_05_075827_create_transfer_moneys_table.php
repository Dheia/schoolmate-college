<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransferMoneysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfer_moneys', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->integer('referrence_no')->nullable();
            $table->string('description');

            //  PAID FROM
            $table->unsignedInteger('paid_from_id');
            $table->double('paid_amount', 8, 2)->default(0);
            $table->enum('paid_from_status', ['Cleared', 'Pending'])->nullable();
            $table->date('paid_date')->nullable();

            //  RECEIVED IN
            $table->unsignedInteger('received_in_id');
            $table->double('receive_in_amount', 8, 2)->default(0);
            $table->enum('received_in_status', ['Cleared', 'Pending'])->nullable();
            $table->date('received_in_date')->nullable();

            $table->timestamps();

            $table->foreign('paid_from_id')->references('id')->on('cash_accounts');
            $table->foreign('received_in_id')->references('id')->on('cash_accounts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transfer_moneys');
    }
}
