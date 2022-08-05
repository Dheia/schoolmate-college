<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOnlinePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('online_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('studentnumber')->unsigned();
            $table->string('amount');
            $table->string('fee')->default(0);
            $table->string('description')->nullable();
            $table->string('email');
            $table->string('pay_id');
            $table->string('status');
            $table->longText('json_response')->nullable();  
            $table->ipAddress('ip_address')->nullable();
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
        Schema::dropIfExists('online_payments');
    }
}
