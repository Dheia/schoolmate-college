<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayrollRunItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payroll_run_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('payroll_run_id')->unsigned();
            $table->integer('employee_id');
            $table->string('full_name');
            $table->json('payroll')->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
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
        Schema::dropIfExists('payroll_run_items');
    }
}
