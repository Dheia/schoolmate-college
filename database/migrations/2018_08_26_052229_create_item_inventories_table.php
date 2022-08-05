<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_inventories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->unique();
            $table->string('barcode');
            $table->string('name');
            $table->string('unit_name')->nullable();
            $table->decimal('cost_price',20,2)->nullable();
            $table->decimal('sale_price',20,2)->nullable();
            $table->string('description');
            $table->integer('income_id')->nullable();
            $table->integer('expense_id')->nullable();
            $table->integer('tax_id')->nullable();
            $table->decimal('quantity_on_hand',20,0);
            $table->decimal('average_cost',20,2)->nullable();
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
        Schema::dropIfExists('item_inventories');
    }
}
