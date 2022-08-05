<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToPayrollRunsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payroll_runs', function (Blueprint $table) {
            $table->boolean('tax')->after('run_by')->default(0);
            $table->enum('tax_type', ['full', 'half'])->after('tax');

            $table->boolean('sss')->after('tax_type')->default(0);
            $table->enum('sss_type', ['full', 'half'])->after('sss');

            $table->boolean('philhealth')->after('sss_type')->default(0);
            $table->enum('philhealth_type', ['full', 'half'])->after('philhealth');

            $table->boolean('hdmf')->after('philhealth_type')->default(0);
            $table->enum('hdmf_type', ['full', 'half'])->after('hdmf');

            $table->boolean('sss_loan')->after('hdmf_type')->default(0);
            $table->enum('sss_loan_type', ['full', 'half'])->after('sss_loan');

            $table->boolean('hdmf_loan')->after('sss_loan_type')->default(0);
            $table->enum('hdmf_loan_type', ['full', 'half'])->after('hdmf_loan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payroll_runs', function (Blueprint $table) {
            $table->dropColumn('tax');
            $table->dropColumn('tax_type');
            $table->dropColumn('sss');
            $table->dropColumn('sss_type');
            $table->dropColumn('philhealth');
            $table->dropColumn('philhealth_type');
            $table->dropColumn('hdmf');
            $table->dropColumn('hdmf_type');
            $table->dropColumn('sss_loan');
            $table->dropColumn('sss_loan_type');
            $table->dropColumn('hdmf_loan');
            $table->dropColumn('hdmf_loan_type');
        });
    }
}
