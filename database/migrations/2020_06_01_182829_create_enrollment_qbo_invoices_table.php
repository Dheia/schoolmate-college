<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnrollmentQboInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enrollment_qbo_invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uniqid');
            $table->string('batch_item_id');
            $table->integer('enrollment_id')->unsigned();
            $table->integer('qbo_id')->unsigned()->nullable();
            $table->longText('items')->nullable();
            $table->boolean('error')->default(0);
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
        Schema::dropIfExists('enrollment_qbo_invoices');
    }
}
