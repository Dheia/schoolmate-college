<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParentUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parent_users', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('photo')->nullable();
            $table->string('firstname');
            $table->string('middlename')->nullable();
            $table->string('lastname');
            $table->enum('gender',['Male','Female']);
            $table->date('birthdate')->nullable();
            $table->string('citizenship')->nullable();
            $table->string('street_number')->nullable();
            $table->string('barangay')->nullable();
            $table->string('city_municipality')->nullable();
            $table->string('province')->nullable();
            $table->string('country')->nullable();
            $table->string('mobile');
            $table->string('telephone')->nullable();
            $table->string('email');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parent_users');
    }
}
