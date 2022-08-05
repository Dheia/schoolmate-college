<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFamilyEmailsToStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('father_email')->after('fatherMobileNumber')->nullable();
            $table->string('mother_email')->after('mothernumber')->nullable();
            $table->string('legal_guardian_email')->after('legal_guardian_contact_number')->nullable();
            $table->string('emergency_email')->after('emergencymobilenumber')->nullable();
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
            $table->dropColumn('father_email');
            $table->dropColumn('mother_email');
            $table->dropColumn('legal_guardian_email');
            $table->dropColumn('emergency_email');
        });
    }
}
