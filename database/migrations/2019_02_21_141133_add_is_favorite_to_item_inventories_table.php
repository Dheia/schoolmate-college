<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsFavoriteToItemInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_inventories', function (Blueprint $table) {
            $table->boolean("is_favorite")->after("average_cost")->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_inventories', function (Blueprint $table) {
            $table->dropColumn("is_favorite");
        });
    }
}
