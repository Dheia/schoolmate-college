<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnsToStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('emergency_contact_number');
            $table->dropColumn('emergency_home_mobile_number');
            $table->dropColumn('contact_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('emergency_contact_number')->nullable()->after('emergency_citizenship');
            $table->string('emergency_home_mobile_number')->nullable()->after('emergency_contact_number');
            $table->string('contact_number')->nullable()->after('province');
        });
    }
}
