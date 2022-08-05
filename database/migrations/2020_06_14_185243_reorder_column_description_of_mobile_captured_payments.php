<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

class ReorderColumnDescriptionOfMobileCapturedPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mobile_captured_payments', function (Blueprint $table) {
            $table->string("description")->after('path_captured_photo')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mobile_captured_payments', function (Blueprint $table) {
            $table->string("description")->change();
        });
    }
}
