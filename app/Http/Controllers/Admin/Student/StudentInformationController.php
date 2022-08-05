<?php

namespace App\Http\Controllers\Admin\Student;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SchoolYear;
use App\Models\Department;

class StudentInformationController extends Controller
{
    public function __construct ($crud, $tab)
    {

    	$crud->crud->addField([
            'label'     			=>  'Status',
            'name'      			=>  'new_or_old',
            'type'      			=>  'select_from_array',
            'options'   			=> 	[ 'new' => 'New Student', 'old' => 'Old Student', ],
            'wrapperAttributes' 	=> 	[ 'class' => 'form-group col-md-3' ],
            'tab' 					=>  $tab,
        ]);

        if($crud->crud->getActionMethod() === "edit") {
            $crud->crud->addField([
                    'name'              => 'schoolyear',
                    'label'             => 'School Year Entered',
                    'type'              => 'select_from_array',
                    'options'           => SchoolYear::all()->pluck('schoolYear', 'id'),
                    'wrapperAttributes' => [ 'class' => 'form-group col-md-3' ],
                    'tab'               => $tab,
            ]);
        } else {
            $crud->crud->addField([
                    'name'              => 'schoolyear',
                    'label'             => 'School Year Entered',
                    'type'              => 'select_from_array',
                    'options'           => SchoolYear::all()->pluck('schoolYear', 'id'),
                    'default'           => SchoolYear::active()->first()->id,
                    'wrapperAttributes' => [ 'class' => 'form-group col-md-3' ],
                    'tab'               => $tab,
            ]);
        }

        $crud->crud->addField([ 
            'name' 					=>  'application',
            'type' 					=>  'date_picker',
            'label' 				=>  'Application Date',
            'value' 				=>  date('Y-m-d'),
            'attributes' 			=> 	[ 'disable' => 'true' ],
            'date_picker_options' 	=>  [
							                'todayBtn' 			=> true,
							                'format' 			=> 'mm-dd-yyyy',
							                'language' 			=> 'en',
							                'todayHighlight'	=> true,
							            ],
            'wrapperAttributes' 	=> 	[ 'class' => 'form-group col-md-4' ],
            'tab' 					=>  $tab,
        ]);

        $crud->crud->addField([  // Select
			'label' 			=> "Department",
			'type' 				=> 'select_from_array',
			'name' 				=> 'department_id',
            'options'           => Department::active()->pluck('name', 'id'),
            'wrapperAttributes' => [ 'class' => 'form-group col-md-3' ],
			'tab' 				=> $tab,
        ]);

        $crud->crud->addField([  // Select
			'label' 			=> "Level",
			'name' 				=> 'level_id',
			'type' 				=> 'select_from_array',
            'options'           => [],
            'wrapperAttributes' => [ 'class' => 'form-group col-md-3' ],
			'tab' 				=> $tab,
        ]);

        $crud->crud->addField([  // Select
			'label' 			=> "Strand",
			'name' 				=> 'track_id', // the db column for the foreign key
			'type'               => 'select_from_array',
            'options'           => [],
            'wrapperAttributes' => [ 'class' => 'form-group col-md-3 track-wrapper' ],
			'tab' 				=> $tab,
        ]);

        $crud->crud->addField([// image
            'label' 		=> "Photo",
            'type' 			=> 'image',
            'name' 			=> 'photo',
            'upload' 		=> true,
            'crop' 			=> true, // set to true to allow cropping, false to disable
            'aspect_ratio' 	=> 1, // ommit or set to 0 to allow any aspect ratio
            // 'prefix' => 'uploads/images/profile_pictures/' // in case you only store the filename in the database, this text will be prepended to the database value
            'tab' 			=> $tab
        ]);

        $crud->crud->addField([
            'label' 			=> 'Student Number', 
            'name' 				=> 'studentnumber',
            'type' 				=> 'text',
            'attributes'		=> [ 'readonly' => true ],
            'wrapperAttributes' => [ 'class' => 'form-group col-md-3    ' ],
            'tab' 				=> $tab,
        ]);


        $crud->crud->addField([   // date_picker
            'label' 			=> 'LRN',
            'type' 				=> 'text',
            'name' 				=> 'lrn',
            'wrapperAttributes' => [ 'class' => 'form-group col-md-3    ' ],
            'tab' 				=> $tab,
        ]);

        $crud->crud->addField([
            'name' => 'clearfix',
            'type'  => 'custom_html',
            'value' => '<div class="clearfix"></div>',
            'tab'               => $tab,
        ])->afterField('lrn');

        $crud->crud->addField([
        	'name' 				=> 'lastname',
            'label' 			=> 'Last Name',
            'wrapperAttributes' => 	[ 'class' => 'form-group col-md-3' ],
            'tab' 				=> $tab,
        ]);

        $crud->crud->addField([
        	'name' 				=> 'firstname',
            'label' 			=> 'First Name',
            'wrapperAttributes' => 	[ 'class' => 'form-group col-md-3' ],
            'tab' 				=> $tab,
        ]);

        $crud->crud->addField([
        	'name' 				=> 'middlename',
            'label' 			=> 'Middle Name',
            'wrapperAttributes' => 	[ 'class' => 'form-group col-md-3' ],
            'tab' 				=> $tab,
        ]);

        $crud->crud->addField([
        	'name' 				=> 'gender', 
            'type' 				=> 'enum',
            'wrapperAttributes' => [ 'class' => 'form-group col-md-3' ],
            'tab' 				=> $tab,
    	]);

        $crud->crud->addField([
        	'name' 				=> 'citizenship', 
            'wrapperAttributes' => [ 'class' => 'form-group col-md-3' ],
            'tab' 				=> $tab,
        ]);

        $crud->crud->addField([
        	'name' 				=> 'religion', 
            'wrapperAttributes' => [ 'class' => 'form-group col-md-3' ],
            'tab' 				=> $tab,
        ]);

        $crud->crud->addField([
            'label'             => 'Contact Number',
            'name'              => 'contactnumber',
            'wrapperAttributes' => [ 'class' => 'form-group col-md-3' ],
            'tab'               => $tab,
        ]);

        $crud->crud->addField([
            'label'             => 'Email',
            'name'              => 'email',
            'wrapperAttributes' => [ 'class' => 'form-group col-md-3' ],
            'tab'               => $tab,
        ]);

        $crud->crud->addField([
            'label'             => 'Place of Birth',
            'name'              => 'birthplace',
            'wrapperAttributes' => [ 'class' => 'form-group col-md-3' ],
            'tab'               => $tab,
        ]);


        $crud->crud->addField([   // date_picker
            'label' 				=> 'Date of Birth',
            'type' 					=> 'date',
            'name' 					=> 'birthdate',
            // 'date_picker_options' 	=> [ 'format' => 'mm/dd/yyyy' ],
            'wrapperAttributes'		=> [ 'class' => 'form-group col-md-3' ],
            'tab' 					=> $tab,
        ]);




        $crud->crud->addField([
            'name'              => 'age',
            'attributes'        => [ 'readonly' => true, 'placeholder' => '' ],
            'wrapperAttributes' => [ 'class' => 'form-group col-md-1' ],
            'tab'               => $tab,
        ]);
    
        $crud->crud->addField([
        	'name' 	=> 'residential_address_label',
        	'type'	=> 'custom_html',
        	'value' => '<h5 style="margin-bottom: 0;"><strong>Residential Address In The Philippines</strong></h5>', 
        	'wrapperAttributes' => [ 'class' => 'col-md-12' ],
        	'tab'	=> $tab
        ])->beforeField('province');

        // dd(config());

        $crud->crud->addField([
            'label'             => 'Province',
            'name'              => 'province',
            'type'              => 'select_from_array',
            'options'           => [],
            'attributes'        => [ 'placeholder' => 'Province', 'id' => 'province' ], 
            'wrapperAttributes' => [ 'class' => 'form-group col-md-2' ],
            'tab'               => $tab,
            'allows_null'       => true
        ]);

        $crud->crud->addField([
            'label'             => 'City/Municipality',
            'name'              => 'city_municipality',
            'type'              => 'select_from_array',
            'options'           => [],
            'attributes'        => [ 'placeholder' => 'City/Municipality', 'id' => 'city_municipality' ],    
            'wrapperAttributes' => [ 'class' => 'form-group col-md-2' ],
            'tab'               => $tab,
            'allows_null'       => true
        ]);



        $crud->crud->addField([
            'label'             => 'Barangay',
            'name'              => 'barangay',
            'type'              => 'select_from_array',
            'options'           => [],
            'attributes'        => [ 'placeholder' => 'Barangay', 'id' => 'barangay' ], 
            'wrapperAttributes' => [ 'class' => 'form-group col-md-2' ],
            'tab'               => $tab,
            'allows_null'       => true
        ]);

        $crud->crud->addField([
        	'label'				=> '',
        	'name'				=> 'street_number',
            'type'              => 'text',
        	'attributes'		=> [ 'placeholder' => 'Address' ],	
            'wrapperAttributes' => [ 
                'class' => 'form-group col-md-6',
            ],
            'tab' 				=> $tab,
        ]);



        
        
    }
}
