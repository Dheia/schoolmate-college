<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StudentSmsTaggings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_sms_taggings', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('access_token');
            $table->string('subscriber_number');
            $table->boolean('is_registered');
            $table->bigInteger('total_sms')->nullable();
            $table->bigInteger('total_paid')->nullable();
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
        Schema::dropIfExists('student_sms_taggings');
    }
}
