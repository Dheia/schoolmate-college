<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class QuickBooksTokenizer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('quick_books_tokenizers', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('accessTokenKey');
            $table->longText('refreshTokenKey');
            $table->datetime('accessTokenExpiresAt');
            $table->datetime('refreshTokenExpiresAt');
            $table->integer('accessTokenValidationPeriod');
            $table->integer('refreshTokenValidationPeriod');

            $table->string('realmID');
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
        Schema::dropIfExists('quick_books_tokenizers');
    }
}
