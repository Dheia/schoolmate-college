<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnTransmutationIdToEncodeGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('encode_grades', function (Blueprint $table) {
            $table->integer('transmutation_id')->after('teacher_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('encode_grades', function (Blueprint $table) {
            $table->dropColumn('transmutation_id');
        });
    }
}
