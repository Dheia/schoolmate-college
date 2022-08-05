<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeMandatoryPagIbigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_mandatory_pag_ibigs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('salary_bracket');
            $table->string('salary_per_month_min');
            $table->string('salary_per_month_max');
            $table->string('employee_share');
            $table->string('employer_share');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_mandatory_pag_ibigs');
    }
}
