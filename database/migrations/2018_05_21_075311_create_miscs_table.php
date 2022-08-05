<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMiscsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('miscs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('grade_year_id');
            $table->string('schoolyear_id');
            $table->string('name');
            $table->string('commitment_payment_id');
            $table->LongText('miscellaneous');
            $table->LongText('payment_scheme')->nullable();
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
        Schema::dropIfExists('miscs');
    }
}
