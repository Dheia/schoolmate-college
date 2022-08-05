<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPostGraduateTertiarySecondaryPrimaryToEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->longText('primary')->after('emergency_relation');
            $table->longText('secondary')->after('primary');
            $table->longText('tertiary')->after('secondary');
            $table->longText('post_graduate')->after('tertiary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('primary')->after('emergency_relation');
            $table->dropColumn('secondary')->after('primary');
            $table->dropColumn('tertiary')->after('secondary');
            $table->dropColumn('post_graduate')->after('tertiary');
        });
    }
}
