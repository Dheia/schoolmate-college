<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUpdateColumnsToStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            
            // MODIFY
            // $table->longText('residentialaddress')->nullable()->change();
            // $table->longText('emergencycontactname')->nullable()->change();
            // $table->longText('emergencymobilenumber')->nullable()->change();

            // NEW
            $table->integer('department_id')->after('schoolyear');
            $table->string('street_number')->after('residentialaddress');
            $table->string('barangay')->after('street_number');
            $table->string('city_municipality')->after('barangay');
            $table->string('province')->after('city_municipality');
            $table->string('contact_number')->after('province');

            $table->string('legal_guardian_lastname')->after('legalguardian');
            $table->string('legal_guardian_firstname')->after('legal_guardian_lastname');
            $table->string('legal_guardian_middlename')->nullable()->after('legal_guardian_firstname');
            $table->string('legal_guardian_citizenship')->nullable()->after('legal_guardian_middlename');
            $table->string('legal_guardian_occupation')->nullable()->after('legal_guardian_citizenship');
            $table->string('legal_guardian_contact_number')->after('legal_guardian_occupation');

            $table->string('emergency_contact_other_relation_ship_to_child')->nullable()->after('emergencycontactname');
            $table->string('emergency_lastname')->nullable()->after('emergency_contact_other_relation_ship_to_child');
            $table->string('emergency_firstname')->nullable()->after('emergency_lastname');
            $table->string('emergency_middlename')->nullable()->after('emergency_firstname');
            $table->string('emergency_citizenship')->nullable()->after('emergency_middlename');
            $table->string('emergency_contact_number')->nullable()->after('emergency_citizenship');
            $table->string('emergency_home_mobile_number')->nullable()->after('emergency_contact_number');

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
            // $table->longText('residentialaddress')->change();
            // $table->longText('emergencycontactname')->change();
            // $table->longText('emergencymobilenumber')->change();

            // NEW
            $table->dropColumn('department_id');
            $table->dropColumn('street_number');
            $table->dropColumn('barangay');
            $table->dropColumn('city_municipality');
            $table->dropColumn('province');
            $table->dropColumn('contact_number');

            $table->dropColumn('legal_guardian_lastname');
            $table->dropColumn('legal_guardian_firstname');
            $table->dropColumn('legal_guardian_middlename');
            $table->dropColumn('legal_guardian_citizenship');
            $table->dropColumn('legal_guardian_occupation');
            $table->dropColumn('legal_guardian_contact_number');

            $table->dropColumn('emergency_contact_other_relation_ship_to_child');
            $table->dropColumn('emergency_lastname');
            $table->dropColumn('emergency_firstname');
            $table->dropColumn('emergency_middlename');
            $table->dropColumn('emergency_citizenship');
            $table->dropColumn('emergency_contact_number');
            $table->dropColumn('emergency_home_mobile_number');
        });
    }
}
