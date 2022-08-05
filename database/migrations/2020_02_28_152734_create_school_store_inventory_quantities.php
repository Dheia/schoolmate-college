<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchoolStoreInventoryQuantities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_store_inventory_quantities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('items');
            $table->boolean('is_start_quantity_set')->default(false);
            $table->boolean('is_end_quantity_set')->default(false);
            $table->integer('created_by');
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
        Schema::dropIfExists('school_store_inventory_quantities');
    }
}
