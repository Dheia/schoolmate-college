<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchoolStoreInventoryQuantityLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_store_inventory_quantity_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('quantity');
            $table->integer('school_store_inventory_id');
            $table->string('description')->nullable();
            $table->integer('created_by_user_id');
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
        Schema::dropIfExists('school_store_inventory_quantity_logs');
    }
}
