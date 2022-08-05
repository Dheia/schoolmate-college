<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropEmployeeMandatoryPhilHealthsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('employee_mandatory_phil_healths');
        Schema::create('employee_mandatory_phil_healths', function (Blueprint $table) {
            $table->increments('id');
            $table->string('monthly_basic_salary_rate');
            $table->longText('computation_salary_table');
            $table->boolean('active')->default(0);
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
        Schema::dropIfExists('employee_mandatory_phil_healths');
        Schema::create('employee_mandatory_phil_healths', function (Blueprint $table) {
            $table->increments('id');
            $table->string("monthly_basic_salary_min");
            $table->string("monthly_basic_salary_max");
            $table->string("monthly_premium_min");
            $table->string("monthly_premium_max");
            $table->string("personal_share_min");
            $table->string("personal_share_max");
            $table->string("employer_share_min");
            $table->string("employer_share_max");
            $table->timestamps();
            $table->softDeletes();
        });
    }

}
