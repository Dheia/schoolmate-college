<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchoolStoreInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_store_inventories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('image');
            $table->string('code');
            $table->string('barcode');
            $table->string('name');
            $table->bigInteger('school_store_category_id');
            $table->bigInteger('unit_name');
            $table->bigInteger('cost_price');
            $table->string('sale_price');
            $table->string('desciption')->nullable();
            $table->bigInteger('income_id');
            $table->bigInteger('expense_id');
            $table->bigInteger('tax_id');
            $table->bigInteger('quantity_on_hand');
            $table->string('average_cost');
            $table->boolean('is_favorite')->default(0);
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
        Schema::dropIfExists('school_store_inventories');
    }
}
