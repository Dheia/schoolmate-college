<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdminFeeToEmployeeSalariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_salaries', function (Blueprint $table) {
            $table->decimal('admin_pay', 65, 2)->default(0.00)->after('salary');
            $table->decimal('other_pay', 65, 2)->default(0.00)->after('admin_pay');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_salaries', function (Blueprint $table) {
            $table->dropColumn('admin_pay');
            $table->dropColumn('other_pay');
        });
    }
}
