<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpendMoneysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spend_moneys', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->integer('referrence_no')->nullable();
            $table->integer('paid_from_id');
            $table->enum('status', array('cleared', 'pending'))->nullable();
            $table->date('received_date')->nullable();
            $table->string('payer');
            $table->string('description');
            $table->longText('accounts');
            $table->longText('notes');
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
        Schema::dropIfExists('spend_moneys');
    }
}
