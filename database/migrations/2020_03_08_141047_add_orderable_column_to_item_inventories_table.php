<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderableColumnToItemInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_inventories', function (Blueprint $table) {
            $table->boolean('orderable')->default(0)->after('is_favorite');
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
            $table->dropColumn('orderable');
        });
    }
}
