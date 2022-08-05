<?php

namespace App\Http\Controllers\Admin\Student;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OtherInformationController extends Controller
{
    public function __construct ($crud, $tab)
    {
        $crud->crud->addField([
            'name'      => 'previousschool', 
            'label'     => 'Name of the previous School',
            'tab'       => $tab,
        ]);

        $crud->crud->addField([
            'name'      => 'previousschooladdress', 
            'label'     => 'Complete address of the above School (including zip code)',
            'tab'       => $tab,
        ]);

        $crud->crud->addField([   // Table
            'name'              => 'schooltable',
            'label'             => 'School(s) attended',
            'type'              => 'child_school_attended_parent',
            'entity_singular'   => 'Line', // used on the "Add X" button
            'columns'           =>  [
                                        [
                                            'label'     => 'Grade/Level (Until)',
                                            'type'      => 'child_school_attended',
                                            'name'      => 'grade_level_until',
                                            'entity'    => 'yearManagement',
                                            'attribute' => 'year',
                                            'model'     => 'App\Models\YearManagement'
                                        ],
                                        [
                                            'label'     => 'Grade/Level (From)',
                                            'type'      => 'child_school_attended',
                                            'name'      => 'grade_level_from',
                                            'entity'    => 'yearManagement', // the method that defines the relationship in your Model
                                            'attribute' => 'year', // foreign key attribute that is shown to user
                                            'model'     => "App\Models\YearManagement", // foreign key model
                                        ],
                                        [
                                            'label'     => 'Name of School',
                                            'type'      => 'child_text',
                                            'name'      => 'school_name',
                                        ],
                                        [
                                            'label'     => 'Year Attended',
                                            'type'      => 'child_text',
                                            'name'      => 'year_attended'
                                        ]
                                    ],
            'max'               => 6, // maximum rows allowed in the table
            'min'               => 0, // minimum rows allowed in the table
            'tab'               => $tab,
        ]);

    	$crud->crud->addField([
			'label' 			=> 'Reading and Writing Proficiency',
			'name' 				=> 'readingwriting', 
			'type' 				=> 'enum',
			'wrapperAttributes' => [ 'class' => 'form-group col-md-6' ],
			'tab' 				=> $tab,
        ]);

        $crud->crud->addField([
            'label' 			=> 'Verbal Proficiency',
            'name' 				=> 'verbalproficiency', 
            'type' 				=> 'enum',
            'wrapperAttributes' => [ 'class' => 'form-group col-md-6' ],
            'tab' 				=> $tab,
        ]);

        $crud->crud->addField([  
            'label' 			=> 'Major language used at home',
            'name' 				=> 'majorlanguages',
            'type' 				=> 'select_from_array',
            'options' 			=> config('marjorLanguage'),
            'attributes' 		=> [ 'id' => 'majorLanguage' ],
            'wrapperAttributes' => [ 'class' => 'form-group col-md-6' ],
            'tab' 				=> $tab
        ]);

        $crud->crud->addField([
            'name' 				=> 'other_language_specify',
            'label' 			=> 'Specify Other Language:',
            'type' 				=> 'text',
            'attributes' 		=> 	[
						                'id' 			=> 'other_language_specify',
						                'disabled' 		=> true,
						                'placeholder' 	=> '(ex. british)'
						            ],
            'wrapperAttributes' => 	[ 'class' => 'form-group col-md-6' ],
            'tab' 				=> $tab,
        ]);

        $crud->crud->addField([   
            'name' 				=> 'otherlanguages',
            'label' 			=> 'Other languages/dialects spoken',
            'type' 				=> 'table',
            'columns' 			=> [ 'languages' => 'List below' ],
            'max' 				=> 10,
            'min' 				=> 1,
            'entity_singular' 	=> 'Line',
            'tab' 				=> $tab,
        ]);

        $crud->crud->addField([ 
            'name' 				=> 'classparticipation',
            'label' 			=> 'List any participation in advanced level classes (i.e., Advanced Math, Gifted or Talented, Gateway, Writing, etc.)',
            'type' 				=> 'table',
            'entity_singular' 	=> 'Line', // used on the "Add X" button
            'columns' 			=> [ 'participation' => 'List below' ],
            'max' 				=> 10,
            'min' 				=> 1,
            'tab' 				=> $tab,
        ]);

        $crud->crud->addField([
        	'name' 			=> 'remedialhelpexplanation',
			'label' 		=> 'Please explain and provide latest testing results.',
			'attributes'	=> [ 'placeholder' => 'Explanation here' ],
			'tab' 			=> $tab,
		]);

        $crud->crud->addField([
            'label' 		=> 'Does your child have any special talent or interest in:',
            'type' 			=> 'checklistcustom',
        	'name' 			=> 'specialtalent',
            'attributes' 	=> [ 'id' => 'specialtalentid', ],
            'tab' 			=> $tab,
        ]);

        $crud->crud->addField([  
			'label' 			=> 'Are there any other information you think the teacher should know about your child?',
			'name' 				=> 'otherinfo',
			'type' 				=> 'toggle',
			'options' 			=> [ 1 => 'Yes', 0 => 'No' ],
			'hide_when' 		=> [ 0 => ['otherinfofield'], ],
			'default' 			=> 0,
			'wrapperAttributes' => [ 'class' => 'form-group col-md-12' ],
			'tab' 				=> $tab,
		]);

        $crud->crud->addField([   
			'name' 		=> 'disciplinaryproblem',
			'label' 	=> 'Has your child ever been asked to leave school because of any behavioral/disciplinary problems?',
			'type' 		=> 'toggle',
			'tab' 		=> $tab,
			'options' 	=> [ 1 => 'Yes', 0 => 'No' ],
			'hide_when' => [ 0 => ['disciplinaryproblemexplanation'], ],
			'default'	=> 0,                
        ]);

        $crud->crud->addField([
            'name' 	=> 'disciplinaryproblemexplanation',
            'tab' 	=> $tab,
            'label' => 'Please explain'
        ]);
    }
}
