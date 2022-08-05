<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAutoApprovePaymentLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auto_approve_payment_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('payment_history_id');
            $table->enum('status', ['pending', 'good', 'retry', 'failed'])->default('pending');
            $table->integer('attempts')->default(0);
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
        Schema::dropIfExists('auto_approve_payment_logs');
    }
}
