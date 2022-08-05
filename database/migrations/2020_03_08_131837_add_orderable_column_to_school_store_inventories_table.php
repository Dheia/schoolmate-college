<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderableColumnToSchoolStoreInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('school_store_inventories', function (Blueprint $table) {
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
        Schema::table('school_store_inventories', function (Blueprint $table) {
            $table->dropColumn('orderable');
        });
    }
}
