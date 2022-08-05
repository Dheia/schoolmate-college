<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayrollRunsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payroll_runs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('payroll_id');
            $table->date('date_from');
            $table->date('date_to');
            $table->enum('status', ['PUBLISHED', 'UNPUBLISH', 'CANCELED'])->default('UNPUBLISH');
            $table->integer('run_by')->unsigned();
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
        Schema::dropIfExists('payroll_runs');
    }
}
