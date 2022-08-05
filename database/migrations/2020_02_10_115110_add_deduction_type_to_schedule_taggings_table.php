<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeductionTypeToScheduleTaggingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schedule_taggings', function (Blueprint $table) {
            $table->enum('deduction_type', ['Based On Schedule', 'Based On Hours Per Week'])->after('schedule_template_id')->default('Based On Schedule');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schedule_taggings', function (Blueprint $table) {
            $table->enum('deduction_type');
        });
    }
}
