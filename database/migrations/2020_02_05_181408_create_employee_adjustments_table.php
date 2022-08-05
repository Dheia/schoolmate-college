<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeAdjustmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_adjustments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('payroll_run_id')->unsigned();
            $table->integer('employee_id');
            $table->string('amount');
            $table->string('description');
            $table->timestamps();

            $table->foreign('payroll_run_id')->references('id')->on('payroll_runs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_adjustments');
    }
}
