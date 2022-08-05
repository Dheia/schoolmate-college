<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLockerInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locker_inventories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->integer('studentnumber')->nullable();
            $table->integer('building_id')->nullable();
            $table->boolean('is_active')->nullable();
            $table->string('description')->nullable();
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
        Schema::dropIfExists('locker_inventories');
    }
}
