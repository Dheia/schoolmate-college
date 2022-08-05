<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('employee_id')->unique();
            $table->string('prefix')->nullable();
            $table->string('firstname');
            $table->string('middlename')->nullable();
            $table->string('lastname');
            $table->string('extname')->nullable();
            $table->string('position');
            $table->date('date_hired');
            $table->string('address1');
            $table->string('address2');
            $table->string('city');
            $table->string('region');
            $table->string('country');
            $table->string('mobile');
            $table->string('telephone');
            $table->string('sss');
            $table->string('phil_no');
            $table->string('pagibig');
            $table->string('tinno');
            $table->string('age');
            $table->enum('gender',['Male','Female']);
            $table->enum('civil_status',['Single','Married','Windower','Separated']);
            $table->date('date_of_birth');
            $table->string('religion');
            $table->string('spouse_name')->nullable();
            $table->integer('spouse_age')->nullable();
            $table->string('spouse_occupation')->nullable();
            $table->string('spouse_company')->nullable();
            $table->string('spouse_company_address')->nullable();
            $table->longText('dependents')->nullable();
            $table->string('fathers_name')->nullable();
            $table->string('fathers_company_name')->nullable();
            $table->string('fathers_company_address')->nullable();
            $table->string('fathers_occupation')->nullable();
            $table->string('fathers_age')->nullable();
            $table->string('fathers_telephone')->nullable();
            $table->string('fathers_mobile')->nullable();
            $table->string('fathers_email')->nullable();
            $table->string('mothers_name')->nullable();
            $table->string('mothers_company_name')->nullable();
            $table->string('mothers_company_address')->nullable();
            $table->string('mothers_occupation')->nullable();
            $table->string('mothers_age')->nullable();
            $table->string('mothers_telephone')->nullable();
            $table->string('mothers_mobile')->nullable();
            $table->string('mothers_email')->nullable();
            $table->longText('sibling')->nullable();
            $table->string('emergency_name')->nullable();
            $table->string('emergency_address')->nullable();
            $table->string('emergency_telephone')->nullable();
            $table->string('emergency_mobile')->nullable();
            $table->string('emergency_relation')->nullable();
            $table->longText('educational')->nullable();
            $table->longText('employment_history')->nullable();
            $table->decimal('salary',10,2)->nullable();
            $table->boolean('currently_employed')->nullable();
            $table->date('time_start')->nullable();
            $table->string('name_of_reference')->nullable();
            $table->string('relationship')->nullable();
            $table->longText('references')->nullable();
            $table->string('medical_condition')->nullable();
            $table->string('past_illness')->nullable();
            $table->string('present_illness')->nullable();
            $table->string('allergies')->nullable();
            $table->string('minor_illness')->nullable();
            $table->string('family_physician')->nullable();
            $table->string('hospital_reference')->nullable();
            $table->boolean('organ_donor')->nullable();
            $table->enum('blood_type',['A','A+','AB+','AB-','B','B+','O+','O-'])->nullable();
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
        Schema::dropIfExists('employees');
    }
}
