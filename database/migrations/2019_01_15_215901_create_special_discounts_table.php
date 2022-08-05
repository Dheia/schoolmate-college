<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpecialDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('special_discounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('amount');
            $table->string('description');
            $table->integer('student_no');
            $table->integer('commitment_payment_id');
            $table->integer('school_year_id');
            $table->integer('grade_level_id');
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
        Schema::dropIfExists('special_discounts');
    }
}
