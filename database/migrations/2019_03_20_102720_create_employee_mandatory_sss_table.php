<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeMandatorySssTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_mandatory_sss', function (Blueprint $table) {
            $table->increments('id');
            $table->string('range_of_compensation_min');
            $table->string('range_of_compensation_max');
            $table->string('monthly_salary_credit');
            $table->string('social_security_er');
            $table->string('social_security_ee');
            $table->string('ec_er');
            $table->string('total_contribution_er');
            $table->string('total_contribution_ee');
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
        Schema::dropIfExists('employee_mandatory_sss');
    }
}
