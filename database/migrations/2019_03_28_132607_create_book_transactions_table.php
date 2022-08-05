<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rfid');
            $table->integer('book_id');
            $table->integer('studentnumber')->nullable();
            $table->integer('employee_id')->nullable();
            $table->date('date_borrowed');
            $table->date('due_date');
            $table->string('date_returned')->nullable();
            $table->boolean('is_returned');
            $table->softDeletes(); 
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
        Schema::dropIfExists('book_transactions');
    }
}