<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaynamicsPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paynamics_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('school_year_id')->unsigned();
            $table->string('studentnumber');
            $table->integer('amount');
            $table->integer('fee');
            $table->string('email');
            $table->string('description');
            $table->integer('payment_method_id')->unsigned();
            $table->string('request_id');
            $table->string('response_id')->nullable();
            $table->datetime('timestamp')->nullable();
            $table->string('rebill_id')->nullable();
            $table->longText('signature')->nullable();
            $table->string('response_code')->nullable();
            $table->string('response_message')->nullable();
            $table->string('response_advise')->nullable();
            $table->boolean('mail_sent')->default(0);
            $table->string('status');
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
        Schema::dropIfExists('paynamics_payments');
    }
}
