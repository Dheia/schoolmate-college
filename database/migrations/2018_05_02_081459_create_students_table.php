<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('studentnumber')->unique()->nullable();   
            $table->date('application')->nullable();
            $table->string('schoolyear')->nullable();
            $table->string('level_id')->nullable();
            $table->longText('photo')->nullable(); //notsure
            $table->string('lastname')->nullable();
            $table->string('firstname')->nullable();
            $table->string('middlename')->nullable();
            $table->enum('gender', ['Male', 'Female']);
            $table->date('birthdate')->nullable();
            $table->string('citizenship')->nullable();
            //$table->string('passport')->nullable();
            $table->string('birthplace')->nullable();
            $table->longText('residentialaddress')->nullable();
            $table->string('email')->nullable();
            $table->string('religion')->nullable();
            $table->longText('living')->nullable();
            $table->string('legalguardian')->nullable();
            $table->string('contactnumberCountryCode')->nullable();
            $table->string('contactnumber')->nullable();
            $table->enum('readingwriting', ['Good', 'Fair', 'Limited', 'None']);
            $table->enum('verbalproficiency', ['Good', 'Fair', 'Limited', 'None']);
            $table->longText('majorlanguages')->nullable();
            $table->longText('other_language_specify')->nullable();
            $table->longText('otherlanguages')->nullable();
            $table->longText('classparticipation')->nullable();
            $table->boolean('remedialhelp')->nullable();
            $table->longText('remedialhelpexplanation')->nullable();
            $table->longText('specialtalent')->nullable(); //remove nullable ----testss
            //$table->string('athletics')->nullable(); //may not be needed
            //$table->string('band')->nullable();//may not be needed
            //$table->string('string')->nullable();//may not be needed
            $table->boolean('otherinfo')->nullable();
            $table->longText('otherinfofield')->nullable(); //new field 
            $table->boolean('disciplinaryproblem')->nullable(); //may not be needed
            $table->longText('disciplinaryproblemexplanation')->nullable();
            //-----------------
            $table->longText('previousschool')->nullable();
            $table->longText('previousschooladdress')->nullable();
            $table->longText('schooltable')->nullable();
            //------------------------------------------------------------------
            $table->enum('father', ['Father', 'Step-father', 'Legal Guardian'])->nullable();
            $table->string('fatherfirstname')->nullable();
            $table->string('fatherlastname')->nullable();
            $table->string('fathermiddlename')->nullable();
            $table->string('fathercitizenship')->nullable();
            //$table->string('fatherpassport')->nullable();//may mot be needed // ----not needed
            $table->string('fathervisastatus')->nullable();
            $table->longText('fatheremployer')->nullable();
            $table->string('fatherofficenumberCountryCode')->nullable();
            $table->string('fatherofficenumber')->nullable();
            $table->longText('fatherdegree')->nullable();
            $table->longText('fatherschool')->nullable();
            $table->string('fatherMobileNumberCountryCode')->nullable();
            $table->string('fatherMobileNumber')->nullable();
            $table->boolean('fatherreceivetext')->nullable();
            //-------------------------------------------------------------------
            $table->enum('mother', ['Mother', 'Step-mother', 'Legal Guardian']);
            $table->string('motherfirstname')->nullable();
            $table->string('motherlastname')->nullable();
            $table->string('mothermiddlename')->nullable();
            $table->string('mothercitizenship')->nullable();
            //$table->string('motherpassport')->nullable(); //may mot be needed // ----not needed
            $table->string('mothervisastatus')->nullable();
            $table->longText('motheremployer')->nullable();
            $table->string('motherOfficeNumberCountryCode')->nullable();
            $table->string('motherOfficeNumber')->nullable();
            $table->longText('motherdegree')->nullable();
            $table->longText('motherschool')->nullable();
            $table->string('mothernumberCountryCode')->nullable();
            $table->string('mothernumber')->nullable();
            $table->boolean('motherreceivetext')->nullable();
            //------------------------------------------------------------------

            $table->longText('emergencycontactname')->nullable();
            $table->longText('emergencyRelationshipToChild')->nullable();
            $table->string('emergencyofficephoneCountryCode')->nullable();
            $table->string('emergencyofficephone')->nullable();
            $table->string('emergencymobilenumberCountryCode')->nullable();
            $table->string('emergencymobilenumber')->nullable();
            $table->longText('emergencyaddress')->nullable();
            $table->string('emergencyhomephoneCountryCode')->nullable();
            $table->string('emergencyhomephone')->nullable();

            $table->boolean('isagree')->nullable();//not sure
            $table->boolean('formiscorrect')->nullable();//not sure

            $table->longText('fathersignature')->nullable();
            $table->longText('mothersignature')->nullable();
            $table->date('date')->nullable();
            //------------------3rd page
            
            $table->boolean('asthma')->nullable();
            $table->boolean('asthmainhaler')->nullable();
            $table->boolean('allergy')->nullable();
            $table->longText('allergies')->nullable();
            $table->longText('allergyreaction')->nullable();
            $table->boolean('drugallergy')->nullable();
            $table->longText('drugallergies')->nullable();
            $table->longText('drugallergyreaction')->nullable();
            $table->boolean('visionproblem')->nullable();
            $table->longText('visionproblemdescription')->nullable();
            $table->boolean('hearingproblem')->nullable();
            $table->longText('hearingproblemdescription')->nullable();
            $table->boolean('hashealthcondition')->nullable();
            $table->longText('healthcondition')->nullable();
            $table->boolean('ishospitalized')->nullable();
            $table->longText('hospitalized')->nullable();
            $table->boolean('hadinjuries')->nullable();
            $table->longText('injuries')->nullable();
            $table->boolean('medication')->nullable();
            $table->longText('medications')->nullable();
            $table->boolean('schoolhourmedication')->nullable();
            //---------------------
            $table->boolean('firstaidd')->nullable();
            $table->boolean('emergencycare')->nullable();
            $table->boolean('hospitalemergencycare')->nullable();
            $table->boolean('oralmedication')->nullable();
            $table->longText('parentsignature')->nullable();
            $table->date('date2')->nullable();

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
        Schema::dropIfExists('students')->nullable();
    }
}
