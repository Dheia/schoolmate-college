<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmartSmsTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smart_sms_tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('accessToken')->nullable();
            $table->integer('expiresIn')->nullable();
            $table->string('tokenType')->nullable();
            $table->longText('refreshToken')->nullable();
            $table->string('grantType')->nullable();
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
        Schema::dropIfExists('smart_sms_tokens');
    }
}
