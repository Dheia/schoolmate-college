<?php

namespace App\Http\Controllers\Admin\Student;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FamilyBackgroundController extends Controller
{
    public function __construct ($crud, $tab)
    {
    	// FATHER FIELDS
        $crud->crud->addField([
           'label' 				=> 'Parent',
           'type' 				=> 'enum',
           'name' 				=> 'father',
           'wrapperAttributes' 	=> [ 'class' => 'form-group col-md-4' ],
           'tab' 			  	=> $tab,
        ]);

        $crud->crud->addField([
           'label' 				     => '',
           'type' 				      => 'radio',
           'name' 				      => 'father_living_deceased',
           'options'          	=> [ 'living' => "Living", 'deceased' => "Deceased" ],
           'wrapperAttributes' 	=> [ 'class' => 'form-group col-md-8' ],
           'inline'      	      => true,
           'default'            => 'living',
           'tab' 			  	      => $tab,
        ]);

        $crud->crud->addField([
        	'name'	=> 'family_bg_father_clearfix',
        	'type'	=> 'custom_html',
        	'value'	=> '<div class="clearfix"></div>',
        	'tab'	=> $tab,
        ])->afterField('father_living_deceased');

        $crud->crud->addField([
           'name' 				=> 'fatherlastname',
           'label' 				=> 'Last Name',
           'wrapperAttributes' 	=> [ 'class' => 'form-group col-md-4' ],
           'tab' 				=> $tab,
        ]);

        $crud->crud->addField([
           'name' 				=> 'fatherfirstname',
           'label' 				=> 'First Name',
           'wrapperAttributes' 	=> [ 'class' => 'form-group col-md-4' ],
           'tab' 				=> $tab,
        ]);

        $crud->crud->addField([
           'name' 				=> 'fathermiddlename',
           'label' 				=> 'Middle Name',
           'tab' 				=> $tab,
           'wrapperAttributes' 	=> [ 'class' => 'form-group col-md-4' ],
        ]);

        $crud->crud->addField([
           'name' 				=> 'fathercitizenship',
           'label' 				=> 'Citizenship',
           'wrapperAttributes' 	=> [ 'class' => 'form-group col-md-4' ],
           'tab' 				=> $tab,
        ], 'update/create/both');

        $crud->crud->addField([
           'label' 				=> 'Philippine Visa Status',
           'type'				=> 'text',
           'name' 				=> 'fathervisastatus',
           'wrapperAttributes' 	=> [ 'class' => 'form-group col-md-4' ],
           'tab' 				=> $tab,
       ]);

        $crud->crud->addField([
            'name' 			=> 'fatherMobileNumberCountryCode',
            'type' 			=> 'hidden',
            'attributes' 	=> [ 'id' => 'fatherMobileNumberCountryCode' ],
        	'tab' 			=> $tab,
        ]);

        $crud->crud->addField([
            'name' 				=> 'fatherMobileNumber',
            'label' 			=> 'Mobile Number',
            'type' 				=> 'country_phone_number',
            'attributes' 		=> [ 'class' => ' form-control fatherMobileNumber' ],
            'wrapperAttributes' => [ 'class' => 'form-group col-md-4' ],
            'tab' 				=> $tab,
        ]);

        $crud->crud->addField([
            'name' 				=> 'father_occupation',
            'label' 			=> 'Father Occupation',
            'type' 				=> 'text',
            'wrapperAttributes' => [ 'class' => 'form-group col-md-4' ],
            'tab' 				=> $tab,
        ]);

        $crud->crud->addField([
           'label' 				=> 'Employer/Organization',
           'type'				=> 'text',
           'name' 				=> 'fatheremployer',
           'wrapperAttributes' 	=> [ 'class' => 'form-group col-md-4' ],
           'tab' 				=> $tab,
        ]);

        $crud->crud->addField([
                'name' 			=> 'fatherofficenumberCountryCode',
                'type' 			=> 'hidden',
                'attributes' 	=> [ 'id' => 'fatherofficenumberCountryCode' ],
                'tab' 			=> $tab,
       	]);

        $crud->crud->addField([
           'name' 				=> 'fatherofficenumber',
           'type' 				=> 'country_phone_number',
           'label' 				=> 'Office Number',
           'attributes' 		=> [ 'class' => 'form-control fatherofficenumber' ],
           'wrapperAttributes' 	=> [ 'class' => 'form-group col-md-4' ],
           'tab' 				=> $tab,
        ]);

        $crud->crud->addField([
           'name'			 	=> 'fatherdegree',
           'label' 				=> 'Graduate Degree',
           'wrapperAttributes' 	=> [ 'class' => 'form-group col-md-6' ],
           'tab' 				=> $tab,
        ]);

        $crud->crud->addField([
           'name' 				=> 'fatherschool',
           'label' 				=> 'School',
           'wrapperAttributes' 	=> [ 'class' => 'form-group col-md-6' ],
           'tab' 				=> $tab,
        ]);

       //  $crud->crud->addField([
       //      'label' 			=> 'Receive text messages about upcoming events, activities and other school announcements on this number?',
       //      'type' 				=> 'radio',
       //  	'name' 				=> 'fatherreceivetext',
       //      'options'     		=> [ 1 => "Yes", 0 => "No" ],
       //      'wrapperAttributes' => [ 'class' => 'form-group col-md-12' ],
       //      'tab' 				=> $tab,
       // ]);
        // END OF FATHER FIELDS



        // MOTHER FIELDS
        $crud->crud->addField([
           'label' 				=> 'Parent',
           'type' 				=> 'enum',
           'name' 				=> 'mother',
           'wrapperAttributes' 	=> [ 'class' => 'form-group col-md-4' ],
           'tab' 			  	=> $tab,
        ]);

        $crud->crud->addField([
           'label' 				=> '',
           'type' 				=> 'radio',
           'name' 				=> 'mother_living_deceased',
           'options'     		=> [ 'living' => "Living", 'deceased' => "Deceased" ],
           'wrapperAttributes' 	=> [ 'class' => 'form-group col-md-8' ],
           'default'            => 'living',
           'inline'      		=> true,
           'tab' 			  	=> $tab,
        ]);

        $crud->crud->addField([
        	'name'	=> 'family_bg_mother_clearfix',
        	'type'	=> 'custom_html',
        	'value'	=> '<div class="clearfix"></div>',
        	'tab'	=> $tab,
        ])->afterField('mother_living_deceased');

        $crud->crud->addField([
           'name' 				=> 'motherlastname',
           'label' 				=> 'Last Name',
           'wrapperAttributes' 	=> [ 'class' => 'form-group col-md-4' ],
           'tab' 				=> $tab,
        ]);

        $crud->crud->addField([
           'name' 				=> 'motherfirstname',
           'label' 				=> 'First Name',
           'wrapperAttributes' 	=> [ 'class' => 'form-group col-md-4' ],
           'tab' 				=> $tab,
        ]);

        $crud->crud->addField([
           'name' 				=> 'mothermiddlename',
           'label' 				=> 'Middle Name',
           'tab' 				=> $tab,
           'wrapperAttributes' 	=> [ 'class' => 'form-group col-md-4' ],
        ]);

        $crud->crud->addField([
           'name' 				=> 'mothercitizenship',
           'label' 				=> 'Citizenship',
           'wrapperAttributes' 	=> [ 'class' => 'form-group col-md-4' ],
           'tab' 				=> $tab,
        ]);

        $crud->crud->addField([
           'label' 				=> 'Philippine Visa Status',
           'type'				=> 'text',
           'name' 				=> 'mothervisastatus',
           'wrapperAttributes' 	=> [ 'class' => 'form-group col-md-4' ],
           'tab' 				=> $tab,
       ]);

        $crud->crud->addField([
            'name' 			=> 'motherMobileNumberCountryCode',
            'type' 			=> 'hidden',
            'attributes' 	=> [ 'id' => 'motherMobileNumberCountryCode' ],
        	'tab' 			=> $tab,
        ]);

        $crud->crud->addField([
            'name' 				=> 'mothernumber',
            'label' 			=> 'Mobile Number',
            'type' 				=> 'country_phone_number',
            'attributes' 		=> [ 'class' => ' form-control motherMobileNumber' ],
            'wrapperAttributes' => [ 'class' => 'form-group col-md-4' ],
            'tab' 				=> $tab,
        ]);

        $crud->crud->addField([
            'name' 				=> 'mother_occupation',
            'label' 			=> 'Mother Occupation',
            'type' 				=> 'text',
            'wrapperAttributes' => [ 'class' => 'form-group col-md-4' ],
            'tab' 				=> $tab,
        ]);

        $crud->crud->addField([
           'label' 				=> 'Employer/Organization',
           'type'				=> 'text',
           'name' 				=> 'motheremployer',
           'wrapperAttributes' 	=> [ 'class' => 'form-group col-md-4' ],
           'tab' 				=> $tab,
        ]);

        $crud->crud->addField([
                'name' 			=> 'motherofficenumberCountryCode',
                'type' 			=> 'hidden',
                'attributes' 	=> [ 'id' => 'motherofficenumberCountryCode' ],
                'tab' 			=> $tab,
       	]);

        $crud->crud->addField([
           'name' 				=> 'motherOfficeNumber',
           'type' 				=> 'country_phone_number',
           'label' 				=> 'Office Number',
           'attributes' 		=> [ 'class' => 'form-control motherofficenumber' ],
           'wrapperAttributes' 	=> [ 'class' => 'form-group col-md-4' ],
           'tab' 				=> $tab,
        ]);

        $crud->crud->addField([
           'name'			 	=> 'motherdegree',
           'label' 				=> 'Graduate Degree',
           'wrapperAttributes' 	=> [ 'class' => 'form-group col-md-6' ],
           'tab' 				=> $tab,
        ]);

        $crud->crud->addField([
           'name' 				=> 'motherschool',
           'label' 				=> 'School',
           'wrapperAttributes' 	=> [ 'class' => 'form-group col-md-6' ],
           'tab' 				=> $tab,
        ]);

       //  $crud->crud->addField([
       //      'label' 			=> 'Receive text messages about upcoming events, activities and other school announcements on this number?',
       //      'type' 				=> 'radio',
       //  	'name' 				=> 'motherreceivetext',
       //      'options'     		=> [ 1 => "Yes", 0 => "No" ],
       //      'wrapperAttributes' => [ 'class' => 'form-group col-md-12' ],
       //      'tab' 				=> $tab,
       // ]);
        // END OF MOTHER FIELDS


        // LEGAL GUARDIAN
        $crud->crud->addField([
          'value' => '<h3>LEGAL GUARDIAN INFORMATION</h3>',
          'type'  => 'custom_html',
          'name'  => 'legal_guardian_label',
          'tab'   => $tab
        ])->afterField('motherreceivetext');

        $crud->crud->addField([
           'name'         => 'legal_guardian_lastname',
           'label'        => 'Last Name',
           'wrapperAttributes'  => [ 'class' => 'form-group col-md-4' ],
           'tab'        => $tab,
        ]);

        $crud->crud->addField([
           'name'         => 'legal_guardian_firstname',
           'label'        => 'First Name',
           'wrapperAttributes'  => [ 'class' => 'form-group col-md-4' ],
           'tab'        => $tab,
        ]);

        $crud->crud->addField([
           'name'         => 'legal_guardian_middlename',
           'label'        => 'Middle Name',
           'tab'        => $tab,
           'wrapperAttributes'  => [ 'class' => 'form-group col-md-4' ],
        ]);

        $crud->crud->addField([
           'name'         => 'legal_guardian_citizenship',
           'label'        => 'Citizenship',
           'wrapperAttributes'  => [ 'class' => 'form-group col-md-4' ],
           'tab'        => $tab,
        ]);


        $crud->crud->addField([
            'name'        => 'legal_guardian_occupation',
            'label'       => 'Occupation',
            'type'        => 'text',
            'wrapperAttributes' => [ 'class' => 'form-group col-md-4' ],
            'tab'         => $tab,
        ]);

        $crud->crud->addField([
            'name'        => 'legal_guardian_contact_number_country_code',
            'type'        => 'hidden',
            'attributes'  => [ 'id' => 'legal_guardian_contact_number_country_code' ],
            'tab'         => $tab,
        ]);

        $crud->crud->addField([
            'name'              => 'legal_guardian_contact_number',
            'label'             => 'Mobile Number',
            'type'              => 'country_phone_number',
            'attributes'        => [ 'class' => ' form-control legal_guardian_contact_number' ],
            'wrapperAttributes' => [ 'class' => 'form-group col-md-4' ],
            'tab'               => $tab,
        ]);

        $crud->crud->addField([
            'name' => 'emergencymobilenumberCountryCode',
            'type' => 'hidden',
            'attributes' => [ 'id' => 'emergencymobilenumberCountryCode' ],
            'tab' => $tab,
        ]);

        // END OF LEGAL GUARDIAN


        // EMERGENCY FIELDS
        $crud->crud->addField([
        	'value'	=> '<h3>EMERGENCY CONTACT INFORMATION</h3>',
        	'type'	=> 'custom_html',
        	'name'	=> 'emergency_label',
        	'tab'	=> $tab
        ])->afterField('legal_guardian_contact_number');

        $crud->crud->addField([
            'label' 			=> 'Relationship To Child',
            'type' 				=> 'select_from_array',
            'options'			=> ['Father' => 'Father', 'Mother' => 'Mother', 'LegalGuardian' => 'LegalGuardian', 'Other' => 'Other' ],
        	'name' 				=> 'emergencyRelationshipToChild',
            'wrapperAttributes' => [ 'class' => 'form-group col-md-6' ],
            'tab' 				=> $tab,
            'allows_null' => true,

       	]);   

       	$crud->crud->addField([
          'label' 			=> '',
          'type' 				=> 'text',
        	'name' 				=> 'emergency_contact_other_relation_ship_to_child',
        	'attributes'		=> [ 'placeholder' => 'Please Specify' ],
          'wrapperAttributes' => [ 'class' => 'form-group col-md-6 emergency_contact_other_relation_ship_to_child hidden' ],
          'tab' 				=> $tab,
       	]);

       	$crud->crud->addField([
        	'value'	=> '<div class="clearfix"></div>',
        	'type'	=> 'custom_html',
        	'name'	=> 'emergency_clearfix',
        	'tab'	=> $tab
        ])->afterField('emergency_contact_other_relation_ship_to_child');

       	$crud->crud->addField([
        	'name' 				=> 'emergency_lastname',
          'label' 			=> 'Lastname',
          'type' 				=> 'text',
          'wrapperAttributes' => [ 'class' => 'form-group col-md-4 emergency_lastname hidden' ],
          'tab' 				=> $tab,
       	]);

       	$crud->crud->addField([
        	'name' 				=> 'emergency_firstname',
            'label' 			=> 'Firstname',
            'type' 				=> 'text',
            'wrapperAttributes' => [ 'class' => 'form-group col-md-4 emergency_firstname hidden' ],
            'tab' 				=> $tab,
       	]);

       	$crud->crud->addField([
        	'name' 				=> 'emergency_middlename',
            'label' 			=> 'Middlename',
            'type' 				=> 'text',
            'wrapperAttributes' => [ 'class' => 'form-group col-md-4 emergency_middlename hidden' ],
            'tab' 				=> $tab,
       	]);

		$crud->crud->addField([
			'label' 			=> 'Address',
			'type'				=> 'text',
			'name' 				=> 'emergencyaddress',
			'wrapperAttributes' => [ 'class' => 'form-group col-md-4' ],	
			'tab' 				=> $tab,
		]);

        $crud->crud->addField([
            'name' => 'emergencymobilenumberCountryCode',
            'type' => 'hidden',
            'attributes' => [ 'id' => 'emergencymobilenumberCountryCode' ],
            'tab' => $tab,
        ]);

        $crud->crud->addField([
        	'name' 				=> 'emergencymobilenumber',
            'label' 			=> 'Mobile Number',
            'type' 				=> 'country_phone_number',
            'attributes' 		=> 	[
						                'class' => 'form-control emergencymobilenumber',
						                'id' 	=> 'emergencymobilenumber',
						            ],
        	'wrapperAttributes' => [ 'class' => 'form-group col-md-4 emergencymobilenumber hidden' ],
            'tab' 				=> $tab,
        ]);

        $crud->crud->addField([
    		'label' 			=> 'Address',
        	'type'				=> 'text',
        	'name' 				=> 'emergencyaddress',
    		'wrapperAttributes' => [ 'class' => 'form-group col-md-4' ],
    		'tab' 				=> $tab,
		]);

		$crud->crud->addField([
			'name' 			=> 'emergencyhomephoneCountryCode',
			'type' 			=> 'hidden',
			'attributes' 	=> [ 'id' => 'emergencyhomephoneCountryCode' ],
			'tab' 			=> $tab,
		]);

		$crud->crud->addField([
			'name' 				=> 'emergencyhomephone',
			'label' 			=> 'Home Phone',
			'type' 				=> 'country_phone_number',
			'attributes' 		=> [ 'class' => 'emergencyhomephone' ],
			'wrapperAttributes' => [ 'class' => 'form-group col-md-4' ],
			'tab' 				=> $tab,
		]);
        // END OF EMERGENCY FIELDS

        // LIVING
        $crud->crud->addField([
            'label' 		=> '',
        	'name' 			=> 'living',
            'type' 			=> 'checkall',
            'attributes' 	=> [ 'id' => 'livingid' ],
            'tab' 			=> $tab,
        ]);
        // END OF LIVING
    }
}
