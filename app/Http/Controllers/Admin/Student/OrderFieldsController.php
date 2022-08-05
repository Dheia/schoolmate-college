<?php

namespace App\Http\Controllers\Admin\Student;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderFieldsController extends Controller
{
    public function __construct ($crud)
    {
    	$crud->crud->orderFields([

    		// STUDENT INFORMATION
    		'new_or_old',
    		'application',
    		'schoolyear',
    		'department_id',
    		'level_id',
    		'track_id',
    		'photo',
    		'studentnumber',
    		'lrn',
    		'lastname',
    		'firstname',
    		'middlename',
    		'gender',
    		'citizenship',
    		'religion',
    		'birthdate',
    		'birthplace',
    		'age',
    		'residential_address_label',
    		'province',
    		'city_municipality',
    		'barangay',
    		'street_number',
    		'contactnumber',
    		'email',

    		// FAMILY BACKGROUND
    			// - FATHER
    		'father',
    		'father_living_deceased',
	        'fatherlastname',
    		'fatherfirstname',
	        'fathermiddlename',
	        'fathercitizenship',
	        'fathervisastatus',
	        'fatherMobileNumberCountryCode',
	        'fatherMobileNumber',
	        'father_occupation',
	        'fatheremployer',
	        'fatherofficenumberCountryCode',
	        'fatherofficenumber',
	        'fatherdegree',
	        'fatherschool',
	        'fatherreceivetext',

	        	// - MOTHER
	        'mother',
    		'mother_living_deceased',
	        'motherlastname',
    		'motherfirstname',
	        'mothermiddlename',
	        'mothercitizenship',
	        'mothervisastatus',
	        'motherMobileNumberCountryCode',
	        'mothernumber',
	        'mother_occupation',
	        'motheremployer',
	        'motherofficenumberCountryCode',
	        'motherOfficeNumber',
	        'motherdegree',
	        'motherschool',
	        'motherreceivetext',

	        	// - EMERGENCY
	        'emergencyRelationshipToChild',
	        'emergency_contact_other_relation_ship_to_child',
			'emergency_lastname',
			'emergency_firstname',
			'emergency_middlename',
	        'emergencyaddress',
			'emergencymobilenumber',
			'emergencyhomephone',
			'living'
			// 'emergency_citizenship',
    	]);
    }
}
