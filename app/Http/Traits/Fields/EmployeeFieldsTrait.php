<?php

namespace App\Http\Traits\Fields;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Log;

trait EmployeeFieldsTrait
{
    /*
    |--------------------------------------------------------------------------
    | PERSONAL BACKGROUND TAB INFO ( FIELDS )
    |--------------------------------------------------------------------------
    */
    public function addPersonalBackgroundTabFields(string $personal_background = null)
    {
        $this->crud->addField([
            'label' => 'Employee No.',
            'type' => 'text',
            'name' => 'employee_id',
            'tab' => $personal_background,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Prefix',
            'type' => 'select_from_array',
            'name' => 'prefix',
            'options' => [
                'Mr' => 'Mr',
                'Ms' => 'Ms',
                'Mrs' => 'Mrs',
            ],
            'allows_null' => true,
            'tab' => $personal_background,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-2'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Firstname',
            'type' => 'text',
            'tab' => $personal_background,
            'name' => 'firstname',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Middlename',
            'type' => 'text',
            'tab' => $personal_background,
            'name' => 'middlename',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-2'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Lastname',
            'type' => 'text',
            'tab' => $personal_background,
            'name' => 'lastname',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Ext Name',
            'type' => 'text',
            'tab' => $personal_background,
            'name' => 'extname',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-2'
            ]
        ]);

        $this->crud->addField([// image
            'label' => "Photo",
            'name' => 'photo',
            'type' => 'image',
            'upload' => true,
            'crop' => true, // set to true to allow cropping, false to disable
            'aspect_ratio' => 1, // ommit or set to 0 to allow any aspect ratio
            // 'prefix' => 'uploads/images/profile_pictures/' // in case you only store the filename in the database, this text will be prepended to the database value
            'tab' => $personal_background,
            // 'wrapperAttributes' => [
            //     'class' => 'form-group col-md-3'
            // ]
        ], 'update/create/both');

        $this->crud->addField([
            'label' => 'Type',
            'type' => 'select_from_array',
            'options' => [
                'Teaching Personnel'        => 'Teaching Personnel', 
                'Non-Teaching Personnel'    => 'Non-Teaching Personnel', 
                'Non-Teaching/Teaching'     => 'Non-Teaching/Teaching'
            ],
            'tab' => $personal_background,
            'name' => 'type',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Position',
            'type' => 'text',
            'tab' => $personal_background,
            'name' => 'position',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Email',
            'type' => 'text',
            'tab' => $personal_background,
            'name' => 'email',
            'attributes' => [
                'placeholder' => 'This will be generated upon activation.',
                'readonly'=>'readonly',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3',
            ]
        ]);

        $this->crud->addField([
            'label' => 'Date Hired',
            'type' => 'date',
            'tab' => $personal_background,
            'name' => 'date_hired',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ]
        ]);

        $this->crud->addField([
            'name' => 'clearfix',
            'type' => 'custom_html',
            'tab' => $personal_background,
            'value' => '<div class="clearfix"></div>'
        ]);

        $this->crud->addField([       // Select2Multiple = n-n relationship (with pivot table)
            'label' => "Academic Department",
            'type' => 'select2_multiple',
            'name' => 'departments', // the method that defines the relationship in your Model
            'entity' => 'departments', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\Models\Department", // foreign key model
            'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
            'wrapperAttributes' => [ 'class' => 'form-group col-md-6' ],
            'options'   => (function ($query) {
                return $query->where('active', 1)->get();
            }),
            'tab' => $personal_background,
            // 'select_all' => true, // show Select All and Clear buttons?
        ]);

        $this->crud->addField([       // Select2Multiple = n-n relationship (with pivot table)
            'label' => "Non Academic Department",
            'type' => 'select2_multiple',
            'name' => 'nonAcademicDepartments', // the method that defines the relationship in your Model
            'entity' => 'nonAcademicDepartments', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\Models\NonAcademicDepartment", // foreign key model
            'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
            'wrapperAttributes' => [ 'class' => 'form-group col-md-6' ],
            'tab' => $personal_background,
            // 'select_all' => true, // show Select All and Clear buttons?
        ]);

        $this->crud->addField([
            'label' => 'Address 1',
            'type' => 'text',
            'tab' => $personal_background,
            'name' => 'address1',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Address 2',
            'type' => 'text',
            'tab' => $personal_background,
            'name' => 'address2',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ]
        ]);

        $this->crud->addField([
            'label' => 'City/Municipality',
            'type' => 'text',
            'tab' => $personal_background,
            'name' => 'city',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Province',
            'type' => 'text',
            'tab' => $personal_background,
            'name' => 'province',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Country',
            'type' => 'text',
            'tab' => $personal_background,
            'name' => 'country',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Mobile',
            'type' => 'number',
            'tab' => $personal_background,
            'name' => 'mobile',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Telephone',
            'type' => 'number',
            'tab' => $personal_background,
            'name' => 'telephone',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        if($this->crud->getActionMethod() == 'create') {
            $this->crud->addField([
                'label'             => 'Age',
                'name'              => 'age',
                'attributes'        => [ 'readonly' => true, 'placeholder' => 'Automatically Computed Based On Birth Date' ],
                'wrapperAttributes' => [ 'class' => 'form-group col-lg-1' ],
                'tab'               => $personal_background,
            ]);
        } else {
            $this->crud->addField([
                'label'             => 'Age',
                'name'              => 'age',
                'attributes'        => [ 'placeholder' => 'Automatically Computed Based On Birth Date' ],
                'wrapperAttributes' => [ 'class' => 'form-group col-lg-1' ],
                'tab'               => $personal_background,
            ]);
        }

        $this->crud->addField([
            'label' => 'Gender',
            'type' => 'select_from_array',
            'tab' => $personal_background,
            'name' => 'gender',
            'options' => [
                'Male' => 'Male',
                'Female' => 'Female',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-3'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Civil Status',
            'type' => 'select_from_array',
            'tab' => $personal_background,
            'name' => 'civil_status',
            'options' => [
                'Single' => 'Single',
                'Married' => 'Married',
                'Widowed' => 'Widowed',
                'Seperated' => 'Seperated',
                'Divorced' => 'Divorced',
                'Single' => 'Single',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-3'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Date of Birth',
            'type' => 'date',
            'tab' => $personal_background,
            'name' => 'date_of_birth',
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-3'
            ]
        ]); 

        $this->crud->addField([
            'label' => 'Religion',
            'type' => 'text',
            'tab' => $personal_background,
            'name' => 'religion',
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-2'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Spouse Name',
            'type' => 'text',
            'tab' => $personal_background,
            'name' => 'spouse_name',
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-5'
            ]
        ]);
        
        $this->crud->addField([
            'label' => 'Spouse Age',
            'type' => 'text',
            'tab' => $personal_background,
            'name' => 'spouse_age',
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-2'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Spouse Occupation',
            'type' => 'text',
            'tab' => $personal_background,
            'name' => 'spouse_occupation',
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-5'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Spouse Company',
            'type' => 'text',
            'tab' => $personal_background,
            'name' => 'spouse_company',
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-4'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Spouse Company Address',
            'type' => 'text',
            'tab' => $personal_background,
            'name' => 'spouse_company_address',
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-8'
            ]
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DOMESTIC TAB INFO ( FIELDS )
    |--------------------------------------------------------------------------
    */
    public function addDomesticTabFields(string $domestic_profile = null)
    {
        $this->crud->addField([   // Table
            'name' => 'domestic_profile',
            'label' => 'Domestic Profile',
            'type' => 'table',
            'entity_singular' => 'Profile', // used on the "Add X" button
            'columns' => [
                'name_of_dependents' => 'Name of Dependents',
                'age' => 'Age',
                'relationship' => 'Relationship'
            ],
            'min' => 1, // maximum rows allowed in the table
            'tab' => $domestic_profile,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | PARENTS TAB INFO ( FIELDS )
    |--------------------------------------------------------------------------
    */
    public function addParentsTabFields(string $parents_information = null)
    {
        /*
         * FATHER INFO
         */
        $this->crud->addField([
            'value' => '<center><h4>FATHER</h4></center><hr>',
            'type' => 'custom_html',
            'name' => 'header_name_father',
            'tab' => $parents_information,
        ]);

        $this->crud->addField([
            'label' => "Father's Name",
            'type' => 'text',
            'name' => 'fathers_name',
            'tab' => $parents_information,
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-6'
            ]
        ]);

        $this->crud->addField([
            'label' => "Occupation",
            'type' => 'text',
            'name' => 'fathers_occupation',
            'tab' => $parents_information,
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-6'
            ]
        ]);

        $this->crud->addField([
            'label' => "Company Name",
            'type' => 'text',
            'name' => 'fathers_company_name',
            'tab' => $parents_information,
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-6'
            ]
        ]);

        $this->crud->addField([
            'label' => "Age",
            'type' => 'text',
            'name' => 'fathers_age',
            'tab' => $parents_information,
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-6'
            ]
        ]);

        $this->crud->addField([
            'label' => "Company Address",
            'type' => 'text',
            'name' => 'fathers_company_address',
            'tab' => $parents_information,
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-6'
            ]
        ]);

        $this->crud->addField([
            'label' => "Telephone",
            'type' => 'text',
            'name' => 'fathers_telephone',
            'tab' => $parents_information,
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-6'
            ]
        ]);

        $this->crud->addField([
            'label' => "Mobile No.",
            'type' => 'text',
            'name' => 'fathers_mobile',
            'tab' => $parents_information,
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-6'
            ]
        ]);

        $this->crud->addField([
            'label' => "Email",
            'type' => 'text',
            'name' => 'fathers_email',
            'tab' => $parents_information,
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-6'
            ]
        ]);

        /*
         * MOTHER INFO
         */
        $this->crud->addField([
            'value' => '<center><h4 style="margin-top: 30px;">MOTHER</h4></center><hr>',
            'type' => 'custom_html',
            'name' => 'header_name_mother',
            'tab' => $parents_information,
        ]);

        $this->crud->addField([
            'label' => "Mother's Name",
            'type' => 'text',
            'name' => 'mothers_name',
            'tab' => $parents_information,
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-6'
            ]
        ]);

        $this->crud->addField([
            'label' => "Occupation",
            'type' => 'text',
            'name' => 'mothers_occupation',
            'tab' => $parents_information,
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-6'
            ]
        ]);

        $this->crud->addField([
            'label' => "Company Name",
            'type' => 'text',
            'name' => 'mothers_company_name',
            'tab' => $parents_information,
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-6'
            ]
        ]);

        $this->crud->addField([
            'label' => "Age",
            'type' => 'text',
            'name' => 'mothers_age',
            'tab' => $parents_information,
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-6'
            ]
        ]);

        $this->crud->addField([
            'label' => "Company Address",
            'type' => 'text',
            'name' => 'mothers_company_address',
            'tab' => $parents_information,
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-6'
            ]
        ]);

        $this->crud->addField([
            'label' => "Telephone",
            'type' => 'text',
            'name' => 'mothers_telephone',
            'tab' => $parents_information,
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-6'
            ]
        ]);

        $this->crud->addField([
            'label' => "Mobile No.",
            'type' => 'text',
            'name' => 'mothers_mobile',
            'tab' => $parents_information,
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-6'
            ]
        ]);

        $this->crud->addField([
            'label' => "Email",
            'type' => 'text',
            'name' => 'mothers_email',
            'tab' => $parents_information,
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-6'
            ]
        ]);

        /*
         * SIBLINGS INFO
         */
        $this->crud->addField([
            'value' => '<center><h4 style="margin-top: 30px;">SIBLINGS</h4></center><hr>',
            'type' => 'custom_html',
            'name' => 'header_in_siblings',
            'tab' => $parents_information,
        ]);

        $this->crud->addField([   // Table
            'name' => 'sibling',
            'label' => 'Siblings',
            'type' => 'table',
            'entity_singular' => 'Sibling', // used on the "Add X" button
            'columns' => [
                'name_of_sibling' => 'Name of Sibling',
                'age' => 'Age',
                'relationship' => 'Relationship'
            ],
            'tab' => $parents_information,
        ]);

        /*
         * IN CASE OF EMERGENCY
         */
        $this->crud->addField([
            'value' => '<center><h4 style="margin-top: 30px;">IN CASE OF EMERGENCY</h4></center><hr>',
            'type' => 'custom_html',
            'name' => 'header_in_case_of_emergency',
            'tab' => $parents_information,
        ]);

        $this->crud->addField([
            'label' => 'Name of Contact Person',
            'type' => 'text',
            'name' => 'emergency_name',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ],
            'tab' => $parents_information,
        ]);

        $this->crud->addField([
            'label' => 'Telephone',
            'type' => 'text',
            'name' => 'emergency_telephone',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
            'tab' => $parents_information,
        ]);

        $this->crud->addField([
            'label' => 'Mobile',
            'type' => 'text',
            'name' => 'emergency_mobile',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
            'tab' => $parents_information,
        ]);

        $this->crud->addField([
            'label' => 'Address',
            'type' => 'text',
            'name' => 'emergency_address',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
            'tab' => $parents_information,
        ]);

        $this->crud->addField([
            'label' => 'Relation',
            'type' => 'text',
            'name' => 'emergency_relation',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
            'tab' => $parents_information,
        ]);


        /*
         * EDUCATIONAL BACKGROUND
         */
        $this->crud->addField([
            'name' => 'educational',
            'type' => 'hidden',
            'tab' => $parents_information,
        ]);

        $this->crud->addField([
            'value' => '<center><h4 style="margin-top: 30px;">EDUCATIONAL BACKGROUND</h4></center><hr>',
            'type' => 'custom_html',
            'name' => 'header_educational_background',
            'tab' => $parents_information,
        ]);


        $this->crud->addField([   // Table
            'name' => 'post_graduate',
            'label' => 'POST GRADUATE',
            'type' => 'table',
            'entity_singular' => 'Profile', // used on the "Add X" button
            'columns' => [
                'year_graduated' => 'Year Graduated',
                'degree' => 'Degree',
                'school' => 'School',
                'address' => 'Address'
            ],
            'min' => 1, // maximum rows allowed in the table
            'max' => 1, // maximum rows allowed in the table
            'tab' => $parents_information,
        ]);

        $this->crud->addField([   // Table
            'name' => 'tertiary',
            'label' => 'TERTIARY',
            'type' => 'table',
            'entity_singular' => 'Profile', // used on the "Add X" button
            'columns' => [
                'year_graduated' => 'Year Graduated',
                'degree' => 'Degree',
                'school' => 'School',
                'address' => 'Address'
            ],
            'min' => 1, // maximum rows allowed in the table
            'max' => 1, // maximum rows allowed in the table
            'tab' => $parents_information,
        ]);

       $this->crud->addField([   // Table
            'name' => 'secondary',
            'label' => 'SECONDARY',
            'type' => 'table',
            'entity_singular' => 'Profile', // used on the "Add X" button
            'columns' => [
                'year_graduated' => 'Year Graduated',
                'degree' => 'Degree',
                'school' => 'School',
                'address' => 'Address'
            ],
            'min' => 1, // maximum rows allowed in the table
            'max' => 1, // maximum rows allowed in the table
            'tab' => $parents_information,
        ]);

        $this->crud->addField([   // Table
            'name' => 'primary',
            'label' => 'PRIMARY',
            'type' => 'table',
            'entity_singular' => 'Profile', // used on the "Add X" button
            'columns' => [
                'year_graduated' => 'Year Graduated',
                'degree' => 'Degree',
                'school' => 'School',
                'address' => 'Address'
            ],
            'min' => 1, // maximum rows allowed in the table
            'max' => 1, // maximum rows allowed in the table
            'tab' => $parents_information,
        ]);

        /*
         * EMPLOYMENT HISTORY
         */
        $this->crud->addField([
            'value' => '<center><h4 style="margin-top: 30px;">EMPLOYMENT HISTORY</h4></center><hr>',
            'type' => 'custom_html',
            'name' => 'header_employment_history',
            'tab' => $parents_information,
        ]);

        $this->crud->addField(
        [   // Table
            'tab' => $parents_information,
            'name' => 'employment_history',
            'label' => 'Employment History',
            'type' => 'child2',
            'entity_singular' => 'Employment History', // used on the "Add X" button
            'columns' => [
                [
                    'label' => 'From',
                    'show_label' => true,
                    'type' => 'child_date',
                    'name' => 'from',
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-3'
                    ]
                ],
                [
                    'label' => 'To',
                    'show_label' => true,
                    'type' => 'child_date',
                    'name' => 'to',
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-3'
                    ]
                ],
                [
                    'label' => 'Position',
                    'show_label' => true,
                    'type' => 'child_text',
                    'name' => 'position',
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-6'
                    ]                    
                ],
                [
                    'label' => 'Company',
                    'show_label' => true,
                    'type' => 'child_text',
                    'name' => 'company',
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-6'
                    ]
                ],
                [
                    'label' => 'Company Address',
                    'show_label' => true,
                    'type' => 'child_text',
                    'name' => 'company_address',
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-6'
                    ]                    
                ],
                [
                    'label' => 'Telephone',
                    'show_label' => true,
                    'type' => 'child_text',
                    'name' => 'telephone',
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-6'
                    ]                    
                ],
                [
                    'label' => 'Reason for leaving',
                    'show_label' => true,
                    'type' => 'child_text',
                    'name' => 'reason_for_leaving',
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-6'
                    ]                    
                ]
                // 'level' => 'GRADE/LEVEL',
                // 'school' => 'NAME OF SCHOOL',
                // 'yearattended' => 'ACADEMIC YEAR ATTENDED',
            ],
            'max' => 3, // maximum rows allowed in the table
        ]);


        /*
         * SALARY
         */
        $this->crud->addField([
            'value' => '<hr>',
            'type' => 'custom_html',
            'name' => 'salary_seperator',
            'tab' => $parents_information,
        ]);


        $this->crud->addField([
            'label' => 'Expected Salary',
            'type' => 'text',
            'name' => 'salary',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
            'tab' => $parents_information
        ]);

        $this->crud->addField([
            'label' => 'Are You Currently Employed?',
            'type' => 'radio',
            'name' => 'currently_employed',
            'options' => [1 => 'Yes', 0 => 'No'],
            'default' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
            'inline'      => true,
            'tab' => $parents_information
        ]);

        $this->crud->addField([
            'label' => 'When Can You Start?',
            'type' => 'date',
            'name' => 'time_start',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ],
            'tab' => $parents_information
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | REFERRAL TAB INFO ( FIELDS )
    |--------------------------------------------------------------------------
    */
    public function addReferralTabFields(string $referral = null)
    {
        $this->crud->addField([
            'label'     => 'Referral?',
            'type'      => 'toggle',
            'name'      => 'referral',
            'tab'       => $referral,
            'options'   => [1 => 'Yes', 0 => 'No'],
            'default'   => 0,
            'hide_when' => [0 => ['relationship', 'name_of_referer']],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
        ]);

        $this->crud->addField([
            'label'     => 'Name of Referral',
            'type'      => 'text',
            'name'      => 'name_of_referer',
            'tab'       => $referral,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-5'
            ],
        ]);

        $this->crud->addField([
            'label'     => 'Relationship',
            'type'      => 'text',
            'name'      => 'relationship',
            'tab'       => $referral,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | REFERENCES TAB INFO ( FIELDS )
    |--------------------------------------------------------------------------
    */
    public function addReferencesTabFields(string $references = null)
    {
        $this->crud->addField(
        [   // Table
            'tab' => $references,
            'name' => 'references',
            'label' => 'Refenences',
            'type' => 'child2',
            'entity_singular' => 'Reference', // used on the "Add X" button
            'columns' => [
                [
                    'label' => 'Name',
                    'show_label' => true,
                    'type' => 'child_text',
                    'name' => 'name',
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-6'
                    ]
                ],
                [
                    'label' => 'Position',
                    'show_label' => true,
                    'type' => 'child_text',
                    'name' => 'position',
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-6'
                    ]                    
                ],
                [
                    'label' => 'Company',
                    'show_label' => true,
                    'type' => 'child_text',
                    'name' => 'company',
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-4'
                    ]
                ],
                [
                    'label' => 'Telephone',
                    'show_label' => true,
                    'type' => 'child_text',
                    'name' => 'telephone',
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-4'
                    ]                    
                ],
                [
                    'label' => 'Mobile No.',
                    'show_label' => true,
                    'type' => 'child_text',
                    'name' => 'mobile_number',
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-4'
                    ]                    
                ],
                [
                    'label' => 'Company Address',
                    'show_label' => true,
                    'type' => 'child_text',
                    'name' => 'company_address',
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-6'
                    ]                    
                ],
                [
                    'label' => 'Email',
                    'show_label' => true,
                    'type' => 'child_text',
                    'name' => 'email',
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-6'
                    ]                    
                ],
                [
                    'value' => '<div class="clearfix"></div>',
                    'type' => 'custom_html',
                    'name' => 'clearfix_for_references'
                ]
            ],
            'max' => 3, // maximum rows allowed in the table
        ]);

        $this->crud->addField([
            'value' => '<div class="clearfix"></div>',
            'type' => 'custom_html',
            'name' => 'clearfix_for_references',
            'tab'   => $references
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | GOVERNMENT TAB INFO ( FIELDS )
    |--------------------------------------------------------------------------
    */
    public function addGovernmentTabFields(string $government = null)
    {
        $this->crud->addField([
            'label' => 'SSS',
            'type' => 'number',
            'name' => 'sss',
            'tab' => $government,
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-3'
            ]
        ]);

        $this->crud->addField([
            'label' => 'PhilHealth No.',
            'type' => 'number',
            'name' => 'phil_no',
            'tab' => $government,
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-3'
            ]
        ]);

        $this->crud->addField([
            'label' => 'HDMF/Pag-IBIG',
            'type' => 'number',
            'name' => 'pagibig',
            'tab' => $government,
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-3'
            ]
        ]);

        $this->crud->addField([
            'label' => 'TIN No.',
            'type' => 'number',
            'name' => 'tinno',
            'tab' => $government,
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-3'
            ]
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | MEDICAL TAB INFO ( FIELDS )
    |--------------------------------------------------------------------------
    */
    public function addMedicalTabFields(string $medical = null)
    {
        $this->crud->addField([
            'label' => 'Medical Condition',
            'type' => 'text',
            'name' => 'medical_condition',
            'tab' => $medical,
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-6'
            ]
        ]);                 

        $this->crud->addField([
            'label' => 'Past Serious Illness/es',
            'type' => 'text',
            'name' => 'past_illness',
            'tab' => $medical,
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-6'
            ]
        ]); 

        $this->crud->addField([
            'label' => 'Present Serious Illness/es',
            'type' => 'text',
            'name' => 'present_illness',
            'tab' => $medical,
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-6'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Allergies',
            'type' => 'text',
            'name' => 'allergies',
            'tab' => $medical,
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-6'
            ]
        ]);          

        $this->crud->addField([
            'label' => 'Minor Illness/es',
            'type' => 'text',
            'name' => 'minor_illness',
            'tab' => $medical,
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-6'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Family Physician',
            'type' => 'text',
            'name' => 'family_physician',
            'tab' => $medical,
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-6'
            ]
        ]);  

        $this->crud->addField([
            'label' => 'Hospital Preference',
            'type' => 'text',
            'name' => 'hospital_reference',
            'tab' => $medical,
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-6'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Organ Donor',
            'type' => 'text',
            'name' => 'organ_donor',
            'tab' => $medical,
            'type' => 'radio',
            'options' => [ // the key will be stored in the db, the value will be shown as label; 
                0 => "Yes",
                1 => "No"
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-2'
            ],
            'inline'      => true,
        ]);         

        $this->crud->addField([
            'label' => 'Blood Type',
            'type' => 'text',
            'name' => 'blood_type',
            'tab' => $medical,
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-4'
            ]
        ]); 
    }
}