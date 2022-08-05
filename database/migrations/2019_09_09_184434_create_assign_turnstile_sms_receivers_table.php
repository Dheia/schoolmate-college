<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignTurnstileSmsReceiversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assign_turnstile_sms_receivers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('department_id')->nullable();
            $table->integer('student_sms_tagging_id');
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
        Schema::dropIfExists('assign_turnstile_sms_receivers');
    }
}
