<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBlastTypeColumnToTextBlasts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('text_blasts', function (Blueprint $table) {
            //
            $table->string('blast_type')->after('subscribers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('text_blasts', function (Blueprint $table) {
            //
            $table->dropColumn('blast_type');
        });
    }
}
