<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmartJwtCredentialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smart_jwt_credentials', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('access_token');
            $table->integer('expires_in');
            $table->string('token_type');
            $table->longText('refresh_token');
            $table->string('grant_type');
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
        Schema::dropIfExists('smart_jwt_credentials');
    }
}
