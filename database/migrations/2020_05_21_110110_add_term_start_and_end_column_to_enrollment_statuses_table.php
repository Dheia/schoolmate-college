<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTermStartAndEndColumnToEnrollmentStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('enrollment_statuses', function (Blueprint $table) {
            $table->date('term_start')->after('term')->nullable();
            $table->date('term_end')->after('term_start')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('enrollment_statuses', function (Blueprint $table) {
            $table->dropColumn('term_start');
            $table->dropColumn('term_end');
        });
    }
}
