<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMethodAndActionToPaymentMethodCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_method_categories', function (Blueprint $table) {
            $table->string('method')->after('name');
            $table->string('action')->after('method');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_method_categories', function (Blueprint $table) {
            $table->dropColumn('method');
            $table->dropColumn('action');
        });
    }
}
