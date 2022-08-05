<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnNameOfBase64photocapture extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mobile_captured_payments', function (Blueprint $table) {
            //
            $table->renameColumn('captured_photo_base64', 'path_captured_photo');
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
            //
        });
    }
}
