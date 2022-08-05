<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifiedColumnsToEnrollmentStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('enrollment_statuses');
        Schema::create('enrollment_statuses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('school_year_id');
            $table->integer('department_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enrollment_statuses');
        Schema::create('enrollment_statuses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('school_year_id');
            $table->integer('department_id');
            $table->string('term');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('early_enrollment_status');
            $table->timestamps();
        });
    }
}
