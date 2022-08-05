<?php

namespace App\Http\Controllers\Admin\Student;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MedicalHistoryController extends Controller
{
	public function __construct ($crud, $tab)
	{
		$crud->crud->addField([
            'label' 			=> 'Asthma',
            'header' 			=> '1. Does your child have any of the following?',
            'name' 				=> 'asthma',
            'type' 				=> 'togglewithheader',
            'inline' 			=> false,
            'options' 			=> [ 1 => 'Yes', 0 => 'No' ],
            'hide_when' 		=> [ 0 => ['asthmainhaler'] ],
            'default' 			=> 0,
            'wrapperAttributes' => [ 'class' => 'form-group col-md-12' ],
            'inline'			=> true,
            'tab' 				=> $tab,
        ]);

        $crud->crud->addField([
            'name'        		=> 'asthmainhaler', // the name of the db column
            'label'       		=> 'Does your child carry an asthma inhaler?', // the input label
            'type'        		=> 'radio',
            'options'     		=> [ 1 => "Yes", 0 => "No" ],
            'wrapperAttributes' => [ 'class' => 'form-group col-md-8' ],
            'inline'      		=> true,
            'tab' 				=> $tab,
        ]);

        $crud->crud->addField([
            'label' 			=> 'Allergies',
            'name' 				=> 'allergy',
            'type' 				=> 'toggle',
            'options' 			=> [ 1 => 'Yes', 0 => 'No' ],
            'hide_when' 		=> [ 0 => ['allergies','allergyreaction'], ],
            'default' 			=> 0,
            'wrapperAttributes' => [ 'class' => 'form-group col-md-12' ],
            'inline' 			=> true,
            'tab' 				=> $tab,
        ]);


		$crud->crud->addField([
			'label' 			=> 'Please specify',
			'name' 				=> 'allergies',
			'attributes' 		=> [ 'placeholder' => 'Allergy(s)' ],
			'wrapperAttributes' => [ 'class' => 'form-group col-md-6' ],
			'tab' 				=> $tab,
		]);

		$crud->crud->addField([
			'name' 				=> 'allergyreaction',
			'label' 			=> 'Reaction',
			'attributes' 		=> [ 'placeholder' => '', ],
			'wrapperAttributes' => [ 'class' => 'form-group col-md-6' ],
			'tab' 				=> $tab,
		]);

		$crud->crud->addField([
            'label' 			=> 'Drug Allergy',
            'name' 				=> 'drugallergy',
            'type' 				=> 'toggle',
            'options' 			=> [ 1 => 'Yes', 0 => 'No' ],
            'default' 			=> 0,
            'hide_when' 		=> [ 0 => ['drugallergies','drugallergyreaction'], ],
            'wrapperAttributes' => [ 'class' => 'form-group col-md-12' ],
            'inline' 			=> true,
            'tab' 				=> $tab,
        ]);

        $crud->crud->addField([
        	'name' 				=> 'drugallergies',
            'label' 			=> 'Please specify',
            'attributes' 		=> [ 'placeholder' => 'Drug Allergy(s)' ],
            'wrapperAttributes' => [ 'class' => 'form-group col-md-6' ],
            'tab' 				=> $tab,
        ]);

        $crud->crud->addField([
         	'name' 				=> 'drugallergyreaction',
            'label' 			=> 'Reaction',
            'attributes' 		=> [ 'placeholder' => '', ],
            'wrapperAttributes' => [ 'class' => 'form-group col-md-6' ],
            'tab' 				=> $tab,
        ]);



        $crud->crud->addField([
            'label' 			=> 'Eye or vision problems',
            'name' 				=> 'visionproblem',
            'type' 				=> 'toggle',
            'options' 			=> [ 1 => 'Yes', 0 => 'No' ],
            'hide_when' 		=> [ 0 => ['visionproblemdescription'], ],
            'wrapperAttributes' => [ 'class' => 'form-group col-md-12' ],
            'default' 			=> 0,
            'inline' 			=> false,
            'tab' 				=> $tab,
        ]);

        $crud->crud->addField([
        	'name' 				=> 'visionproblemdescription',
            'label' 			=> 'Description',
            'tab' 				=> $tab,
            'wrapperAttributes' => [ 'class' => 'form-group col-md-12' ],
            'attributes' 		=> [ 'placeholder' => '', ],
        ]);

        $crud->crud->addField([
            'label' 			=> 'Ear or hearing problems',
            'name' 				=> 'hearingproblem',
            'type' 				=> 'toggle',
            'options' 			=> [ 1 => 'Yes', 0 => 'No' ],
            'hide_when' 		=> [ 0 => ['hearingproblemdescription'], ],
            'wrapperAttributes' => [ 'class' => 'form-group col-md-12' ],
            'default' 			=> 0,
            'inline' 			=> false,
            'tab' 				=> $tab,
        ]);

        $crud->crud->addField([
        	'name' 				=> 'hearingproblemdescription',
        	'label' 			=> 'Description',
        	'wrapperAttributes' => [ 'class' => 'form-group col-md-12' ],
	        'attributes' 		=> [ 'placeholder' => '', ],
        	'tab' 				=> $tab,
        ]);
        

        $crud->crud->addField([
            'label' 			=> '2. Any other health condition that the school should be aware of (e.g epilepsy, diabetes, etc.)',
            'name' 				=> 'hashealthcondition',
            'type' 				=> 'toggle',
            'options' 			=> [ 1 => 'Yes', 0 => 'No' ],
            'hide_when' 		=> [ 0 => ['healthcondition'], ],
            'wrapperAttributes' => [ 'class' => 'form-group col-md-12' ],
            'default' 			=> 0,
            'inline' 			=> false,
            'tab' 				=> $tab,
        ]);

        $crud->crud->addField([
        	'label' 			=> 'Please specify or explain',
        	'name' 				=> 'healthcondition',
        	'attributes' 		=> [ 'placeholder' => '',],
        	'wrapperAttributes' => [ 'class' => 'form-group col-md-12' ],
        	'tab' 				=> $tab,
        ]);

        $crud->crud->addField([
            'label' 			=> '3. Has your child recently been hospitalized?',
            'name' 				=> 'ishospitalized',
            'type' 				=> 'toggle',
            'options' 			=> [ 1 => 'Yes', 0 => 'No' ],
            'hide_when' 		=> [ 0 => ['hospitalized'], ],
            'default' 			=> 0,
            'wrapperAttributes' => [ 'class' => 'form-group col-md-12' ],
            'inline' 			=> true,
            'tab' 				=> $tab,
        ]);

        $crud->crud->addField([
        	'name' 				=> 'hospitalized',
        	'label' 			=> 'when? why?',
        	'attributes' 		=> [ 'placeholder' => '', ],
        	'wrapperAttributes' => [ 'class' => 'form-group col-md-12' ],
        	'tab' 				=> $tab,
		]);

        $crud->crud->addField([
            'label' 			=> '4. Has your child recently had any serious injuries?',
            'name' 				=> 'hadinjuries',
            'type' 				=> 'toggle',
            'options' 			=> [ 1 => 'Yes', 0 => 'No' ],
            'hide_when' 		=> [ 0 => ['injuries'], ],
            'wrapperAttributes' => [ 'class' => 'form-group col-md-12' ],
            'default' 			=> 0,
            'inline' 			=> true,
            'tab' 				=> $tab,
        ]);

        

        $crud->crud->addField([
        	'label' 			=> 'when? why?',
        	'name' 				=> 'injuries',
        	'attributes' 		=> [ 'placeholder' => '', ],
        	'wrapperAttributes' => [ 'class' => 'form-group col-md-12' ],
        	'tab' 				=> $tab,
        ]);

        $crud->crud->addField([
            'label' 			=> '5. Is your child on a regular medication?',
            'name' 				=> 'medication',
            'type' 				=> 'toggle',
            'options' 			=> [ 1 => 'Yes', 0 => 'No' ],
            'hide_when' 		=> [ 0 => ['medications'], ],
            'wrapperAttributes' => [ 'class' => 'form-group col-md-12' ],
            'inline' 			=> true,
            'default' 			=> 0,
            'tab' 				=> $tab,
        ]);

        $crud->crud->addField([
        	'name' 				=> 'medications',
        	'label' 			=> 'Name of medication(s) and frequency',
        	'attributes' 		=> [ 'placeholder' => '', ],
        	'wrapperAttributes' => [ 'class' => 'form-group col-md-12' ],
        	'tab' 				=> $tab,
    	]);

        $crud->crud->addField([
            'tab' 				=> $tab,
            'label'       		=> 'Does your child need to take any medication(s) during school hours?', // the input label
            'type'        		=> 'radio',
            'options'     		=> 	[ // the key will be stored in the db, the value will be shown as label; 
		                                1 => "Yes (if yes, a letter from the Medical Doctor must be submitted and be kept on file and medication(s) will also be kept in school, to be dispensed only by teacher or authorized person.)",
		                                0 => "No"
		                            ],
            'wrapperAttributes' => [ 'class' => 'form-group col-md-12' ],
            'inline'      		=> true,
            'name'        		=> 'schoolhourmedication',
        ]);

        $crud->crud->addField([   
	        'header' 			=> 'I give consent for my child to receive the following:',
	        'name'        		=> 'firstaidd', // the name of the db column
	        'label'       		=> '1. Minor first aid', // the input label
	        'type'        		=> 'radiowithheader',
	        'options'     		=> [ 1 => "Yes", 0 => "No" ],
	        'wrapperAttributes' => [ 'class' => 'form-group col-md-12' ],
	        'inline'      		=> true,
	        'tab' 				=> $tab,
        ]);

        $crud->crud->addField([   
            'name'        		=> 'emergencycare', // the name of the db column
            'tab' 				=> $tab,
            'label'       		=> '2. Emergency care', // the input label
            'type'        		=> 'radio',
            'options'     		=> [ 1 => "Yes", 0 => "No" ],
            'inline'      		=> true,
            'wrapperAttributes' => [ 'class' => 'form-group col-md-12' ]
        ]);

        $crud->crud->addField([   
            'name'        		=> 'hospitalemergencycare', // the name of the db column
            'label'       		=> '3. Emergency care at the nearest hospital', // the input label
            'type'        		=> 'radio',
            'options'     		=> [ 1 => "Yes", 0 => "No"],
            'wrapperAttributes' => [ 'class' => 'form-group col-md-12' ],
            'inline'      		=> true, 
            'tab' 				=> $tab,
        ]);

        $crud->crud->addField([   
	        'label'       		=> '4. Oral non-prescription medication', // the input label
	        'name'        		=> 'oralmedication', // the name of the db column
	        'type'        		=> 'radio',
	        'options'     		=> [ 1 => "Yes", 0 => "No" ],
            'wrapperAttributes' => [ 'class' => 'form-group col-md-12' ],
            'inline'	      	=> true,
	        'tab' 				=> $tab,
		]);

	}
}
