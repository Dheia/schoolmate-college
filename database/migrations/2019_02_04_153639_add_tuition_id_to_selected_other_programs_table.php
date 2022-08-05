<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTuitionIdToSelectedOtherProgramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('selected_other_programs', function (Blueprint $table) {
            $table->integer('tuition_id')->after('grade_level_id');
        });

        Schema::table('special_discounts', function (Blueprint $table) {
            $table->integer('tuition_id')->after('grade_level_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('selected_other_programs', function (Blueprint $table) {
            $table->dropColumn('tuition_id');
        });

        Schema::table('special_discounts', function (Blueprint $table) {
            $table->dropColumn('tuition_id');
        });
    }
}
