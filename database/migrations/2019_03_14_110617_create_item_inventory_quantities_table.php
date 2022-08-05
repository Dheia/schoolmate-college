<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemInventoryQuantitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_inventory_quantities', function (Blueprint $table) {
            $table->increments('id');
            // $table->string('code');
            $table->longText('items');
            $table->boolean('is_start_quantity_set')->default(false);
            $table->boolean('is_end_quantity_set')->default(false);
            // $table->integer('start_quantity')->nullable();
            // $table->integer('end_quantity')->nullable();
            // $table->date('start_date');
            // $table->date('end_date')->nullable();
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
        Schema::dropIfExists('item_inventory_quantities');
    }
}
