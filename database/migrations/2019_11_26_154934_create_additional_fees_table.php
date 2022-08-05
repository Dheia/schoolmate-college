<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdditionalFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('additional_fees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('enrollment_id');
            $table->bigInteger('invoice_no')->nullable();
            $table->bigInteger('user_id');
            $table->bigInteger('qbo_id')->nullable();
            $table->string('amount');
            $table->string('description');
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
        Schema::dropIfExists('additional_fees');
    }
}
