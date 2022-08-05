<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsQboToSpecialDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('special_discounts', function (Blueprint $table) {
            $table->enum('discount_category', ['Discount', 'Grant'])->after('user_id')->nullable();
            $table->enum('discount_type', ['Amount', 'Percentage'])->after('discount_category')->nullable();
            $table->integer('qbo_id')->after('discount_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('special_discounts', function (Blueprint $table) {
            $table->dropColumn('discount_category');
            $table->dropColumn('discount_type');
            $table->dropColumn('qbo_id');
        });
    }
}
