<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnsOtherProgramsAndServicesPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('selected_other_programs', function (Blueprint $table) {
            $table->dropColumn('student_no');
            $table->dropColumn('commitment_payment_id');
            $table->dropColumn('grade_level_id');
            $table->dropColumn('tuition_id');
            $table->dropColumn('school_year_id');
        });

        Schema::table('selected_other_services', function (Blueprint $table) {
            $table->dropColumn('student_no');
            $table->dropColumn('commitment_payment_id');
            $table->dropColumn('grade_level_id');
            $table->dropColumn('tuition_id');
            $table->dropColumn('school_year_id');
        });

        Schema::table('special_discounts', function (Blueprint $table) {
            $table->dropColumn('student_no');
            $table->dropColumn('commitment_payment_id');
            $table->dropColumn('grade_level_id');
            $table->dropColumn('tuition_id');
            $table->dropColumn('school_year_id');
        });

        Schema::table('payment_histories', function (Blueprint $table) {
            $table->dropColumn('student_no');
            $table->dropColumn('commitment_payment_id');
            $table->dropColumn('grade_level_id');
            $table->dropColumn('tuition_id');
            $table->dropColumn('school_year_id');
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
            $table->integer('student_no');
            $table->integer('commitment_payment_id');
            $table->integer('tuition_id');
            $table->integer('grade_level_id');
            $table->integer('school_year_id');
        });
        
        Schema::table('selected_other_services', function (Blueprint $table) {
            $table->integer('student_no');
            $table->integer('commitment_payment_id');
            $table->integer('tuition_id');
            $table->integer('grade_level_id');
            $table->integer('school_year_id');
        });

        Schema::table('special_discounts', function (Blueprint $table) {
            $table->integer('student_no');
            $table->integer('commitment_payment_id');
            $table->integer('tuition_id');
            $table->integer('grade_level_id');
            $table->integer('school_year_id');
        });

        Schema::table('payment_histories', function (Blueprint $table) {
            $table->integer('student_no');
            $table->integer('commitment_payment_id');
            $table->integer('tuition_id');
            $table->integer('grade_level_id');
            $table->integer('school_year_id');
        });
    }
}
