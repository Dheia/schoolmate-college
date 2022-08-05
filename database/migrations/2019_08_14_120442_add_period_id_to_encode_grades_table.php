<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPeriodIdToEncodeGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('encode_grades', function (Blueprint $table) {
            $table->integer('period_id')->after('section_id');
            $table->boolean('submitted')->after('section_id');
            $table->timestamp('submitted_at')->after('rows')->nullable();
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
            $table->dropColumn('period_id');
            $table->dropColumn('submitted');
            $table->dropColumn('submitted_at');
        });
    }
}
