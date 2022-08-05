<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsOtherProgramsAndServicesPaymentsSpecialDiscountsPaymentHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('selected_other_programs', function (Blueprint $table) 
        {
            $table->integer('enrollment_id')->after('id');
        });

        Schema::table('selected_other_services', function (Blueprint $table) 
        {
            $table->integer('enrollment_id')->after('id');
        });

        Schema::table('special_discounts', function (Blueprint $table) 
        {
            $table->integer('enrollment_id')->after('id');
        });

        Schema::table('payment_histories', function (Blueprint $table) 
        {
            $table->integer('enrollment_id')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('selected_other_programs', function (Blueprint $table) 
        {
            $table->dropColumn('enrollment_id');
        });
        Schema::table('selected_other_services', function (Blueprint $table) 
        {
            $table->dropColumn('enrollment_id');
        });
        Schema::table('special_discounts', function (Blueprint $table) 
        {
            $table->dropColumn('enrollment_id');
        });
        Schema::table('payment_histories', function (Blueprint $table) 
        {
            $table->dropColumn('enrollment_id');
        });
    }
}
