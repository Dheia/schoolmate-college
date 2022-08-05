<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\SubjectMappingRequest as StoreRequest;
use App\Http\Requests\SubjectMappingRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;

use App\Models\Department;
use App\Models\SubjectMapping;
use App\Models\SubjectManagement;
use App\Models\CurriculumManagement;

/**
 * Class SubjectMappingCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class SubjectMappingCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\SubjectMapping');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/subject-mapping');
        $this->crud->setEntityNameStrings('Subject Mapping', 'Subject Mappings');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        // add asterisk for fields that are required in SubjectMappingRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->enableDetailsRow();
        $this->crud->allowAccess("details_row");
        
        $this->crud->addClause('levelOrder');

        /*
        |--------------------------------------------------------------------------
        | REMOVE COLUMNS
        |--------------------------------------------------------------------------
        */
        $this->crud->removeColumns(['subjects']);
        
        /*
        |--------------------------------------------------------------------------
        | COLUMNS
        |--------------------------------------------------------------------------
        */
        $this->crud->addColumn([
            'label' => 'Curriculum',
            'type'  => 'select',
            'name'  => 'curriculum_id',
            'entity' => 'curriculum',
            'attribute' => 'curriculum_name',
            'model' => 'App\Models\CurriculumManagement',
        ]);

        $this->crud->addColumn([
            'label' => 'Department',
            'type'  => 'select',
            'name'  => 'department_id',
            'entity' => 'department',
            'attribute' => 'name',
            'model' => 'App\Models\DepartmentManagement',
        ]);

        $this->crud->addColumn([
            'label' => 'Level',
            'type'  => 'select',
            'name'  => 'level_id',
            'entity' => 'level',
            'attribute' => 'year',
            'model' => 'App\Models\YearManagement',
        ]);

        $this->crud->addColumn([
            'label'  => 'Term Type',
            'type'  => 'select',
            'name'  => 'term_id',
            'entity' => 'term',
            'attribute' => 'type',
            'model' => 'App\Models\TermManagement',
        ]);

        $this->crud->addColumn([
            'label'  => 'Term',
            'type'  => 'text',
            'name'  => 'term_type',
        ])->afterColumn('term_id');

        $this->crud->addColumn([
            'label'             => 'Course',
            'type'              => 'select',
            'name'              => 'course_id',
            'entity'            => 'course',
            'attribute'         => 'name',
            'model'             => 'App\Models\CourseManagement',
            'wrapperAttributes' => [ 'class' => 'form-group col-md-6 course-wrapper hidden' ]
        ]);

        $this->crud->addColumn([
            'label'  => 'Track',
            'type'  => 'select',
            'name'  => 'track_id',
            'entity' => 'track',
            'attribute' => 'code',
            'model' => 'App\Models\TrackManagement',
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIELDS
        |--------------------------------------------------------------------------
        */
        $this->crud->addField([
            'name' => 'subject_mapping_scripts',
            'type' => 'subjectMapping.script',
        ]);

        $this->crud->addField([
            'label' => 'Curriculum',
            'type'  => 'select',
            'name'  => 'curriculum_id',
            'entity' => 'curriculum',
            'attribute' => 'curriculum_name',
            'model' => 'App\Models\CurriculumManagement',
            'wrapperAttributes' => [ 'class' => 'form-group col-md-12' ]
        ]);

        $this->crud->addField([
            'value' => '<div class="clearfix"></div>',
            'type' => 'custom_html',
            'name' => 'clearfix',
        ])->afterField('curriculum_id');

        $this->crud->addField([  // Select
            'label'             => "Department",
            'type'              => 'select_from_array',
            'name'              => 'department_id',
            'options'           => Department::active()->pluck('name', 'id'),
            'wrapperAttributes' => [ 'class' => 'form-group col-md-6' ]
        ]);

        $this->crud->addField([
            'label'             => 'Level',
            'type'              => 'select_from_array',
            'name'              => 'level_id',
            'options'           => [],
            'wrapperAttributes' => [ 'class' => 'form-group col-md-6' ]
        ]);

        $this->crud->addField([
            'label'  => 'Term Type',
            'type'  => 'select_from_array',
            'name'  => 'term_id',
            'options' => [],
            // 'entity' => 'term',
            // 'attribute' => 'type',
            // 'model' => 'App\Models\TermManagement',
            'wrapperAttributes' => [ 'class' => 'form-group col-md-6' ]
        ]);

        $this->crud->addField([
            'label'  => 'Term',
            'type'  => 'select_from_array',
            'name'  => 'term_type',
            'options' => [],
            'wrapperAttributes' => [ 'class' => 'form-group col-md-6' ]
        ])->afterField('term_id');

        $this->crud->addField([
            'label'             => 'Track',
            'type'              => 'select_from_array',
            'name'              => 'track_id',
            'options'           => [],
            'wrapperAttributes' => [ 'class' => 'form-group col-md-6 track-wrapper hidden' ]
        ]);

        $this->crud->addField([
            'label'             => 'Course',
            'type'              => 'select_from_array',
            'name'              => 'course_id',
            'options'           => [],
            'wrapperAttributes' => [ 'class' => 'form-group col-md-6 course-wrapper hidden' ]
        ]);

        $this->crud->addField(
            [
                'name' => 'subjects',
                'label' => '<h3 class="text-center" style="margin-bottom: 20px;">Subjects</h3>',
                'type' => 'subjectMapping.child_subjects',
                'entity_singular' => 'Subject', // used on the "Add X" button
                'columns' => [
                    [
                        'label'      => 'Subject Code',
                        'type'       => 'child_select',
                        'name'       => 'subject_code',
                        'entity'     => 'subject',
                        'attribute'  => 'subject_code',
                        'model'      => 'App\Models\SubjectManagement',
                        'attributes' => [ 'class' => 'form-control select2', 'ng-change' => 'changedSubject(item, $index)' ]
                    ],
                    [
                        'label'      => 'Subject Title',
                        'type'       => 'child_text',
                        'name'       => 'subject_title',
                        'attributes' => [ 'readonly' => 'readonly' ],
                    ],
                    // [
                    //     'label'      => 'Subject Description',
                    //     'type'       => 'child_text',
                    //     'name'       => 'subject_description',
                    //     'attributes' => [ 'id' => 'subject_description', 'readonly' => 'readonly' ]
                    // ],
                    [
                        'label'      => 'Percentage',
                        'type'       => 'child_text',
                        'name'       => 'percentage',
                        'attributes' => [ 'id' => 'percentage', 'readonly' => 'readonly' ],
                    ],
                   
                    [
                        'label'      => 'Lec Minutes',
                        'type'       => 'child_number',
                        'name'       => 'lec_min',
                        'attributes' => [ 'id' => 'lec_min'],
                    ],
                    [
                        'label'      => 'Lab Minutes',
                        'type'       => 'child_number',
                        'name'       => 'lab_min',
                        'attributes' => [ 'id' => 'lab_min'],
                    ],
                    [
                        'label'      => 'Pre Requisite',
                        'type'       => 'child_select',
                        'name'       => 'pre_requisite',
                        'entity'     => 'subject',
                        'attribute'  => 'subject_title',
                        'model'      => 'App\Models\SubjectManagement',
                        'attributes' => [ 'class' => 'form-control select2'],
                    ],
                    [
                        'label'      => 'No. Of Unit',
                        'type'       => 'child_text',
                        'name'       => 'no_unit',
                        'attributes' => [ 'id' => 'no_unit', 'readonly' => 'readonly' ],
                    ],
                    [
                        'label'      => 'Price',
                        'type'       => 'child_text',
                        'name'       => 'price',
                        'attributes' => [ 'id' => 'price', 'readonly' => 'readonly' ],
                    ]
                ],
                'min' => 1
        ]);
        $this->crud->addButtonFromView('line', 'Print', 'curriculum.subjectMapping.print', 'end');
    }

    public function store(StoreRequest $request)
    {
        $subject_mapping =  SubjectMapping::where([
                                'curriculum_id' => $request->curriculum_id,
                                'department_id' => $request->department_id,
                                'level_id'  => $request->level_id,
                                'track_id'  => $request->track_i,
                                'term_type' => $request->term_type
                            ]);

        if($subject_mapping->exists()) {
            \Alert::warning("This data is already exists.")->flash();
            return redirect()->back();
        }
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function showDetailsRow($id)
    {
        $this->crud->hasAccessOrFail('details_row');
        $this->data['entry'] = $this->crud->model::where('id', $id)->first();

        $parent = [];
        $children = [];

        if($this->data['entry'] !== null) {
            $subjects = $this->data['entry']->subjects;
            foreach ($subjects as $subject) {

                $subject->subject_id = $subject->subject_code;
                $subject_code = SubjectManagement::where('id', $subject->subject_code)->first();
                $subject->subject_code = $subject_code->subject_code;

                if(!isset($subject->parent_of)) {

                    $parent[] = $subject;
                } else {
                    $children[] = $subject;
                }
            }
        }

        $this->data['crud']     = $this->crud;
        $this->data['parent']   = $parent;
        $this->data['children'] = $children;

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view('crud::studentMapping.details_row', $this->data);
    }
    public function print ($id)
    {
        // ini_set('max_execution_time', 300);
        // ini_set('memory_limit', -1);
        // // $curriculum_subjects = $this->crud->model::find($id)->with(['subjectMappings' => function ($query) {
        // //                                                         $query->with(['curriculum', 'department', 'level', 'term', 'track']);
        // //                                                     }])->first();

        // $curriculum_subjects = $this->crud->model::where('id', $id)->with(['subjectMappings' => function ($query) {
        //                                                         $query->with(['curriculum', 'department', 'level', 'term', 'track']);
        //                                                     }])->first();

        // // if($curriculum_subjects == null)
        // // {
        // //     abort(404);
        // // }
        // // // dd($curriculum_subjects);
        // // $pdf = \App::make('dompdf.wrapper');
        // // // $pdf->setPaper(array(0, 0, 612, 936), 'portrait');

        // // $pdf->loadHTML( view('curriculum.print', compact('curriculum_subjects')) );
        // // return $pdf->stream();

        $this->data['entry'] = $entry = $this->crud->model::where('id', $id)->first();

        $subjectDatas = [];
        $children = [];

        if($this->data['entry'] !== null) {
            $subjects = $this->data['entry']->subjects;
            foreach ($subjects as $subject) {

                $subject->subject_id = $subject->subject_code;
                $subject_code = SubjectManagement::where('id', $subject->subject_code)->first();
                $subject->subject_code = $subject_code->subject_code;

                if(!isset($subject->parent_of)) {

                    $subjectDatas[] = $subject;
                } else {

                    $children[] = $subject;
                }
            }
        }
        $this->data['crud']     = $this->crud;
        $this->data['subjectDatas']   = $subjectDatas;
        $this->data['children'] = $children;

        $schoollogo      = config('settings.schoollogo') ? (string)\Image::make(config('settings.schoollogo'))->encode('data-url') : null;
        $schoolmate_logo = (string)\Image::make('images/schoolmate_logo.jpg')->encode('data-url');
         return view('curriculum.subjectMapping.generateReport', compact('entry', 'subjectDatas','schoollogo','schoolmate_logo'))->with('data');

    }

    public function getSubjects(Request $request)
    {
        $response = [
            'status'  => null,
            'message' => null,
            'data'    => null
        ];

        $curriculum_id = $request->curriculum_id;
        $department_id = $request->department_id;
        $level_id      = $request->level_id;
        $track_id      = $request->track_id;
        $term_type     = $request->term_type;

        if(!$request->curriculum_id || !$request->department_id || !$request->level_id || !$request->term_type) {
            $response = [
                'status'  => 'error',
                'message' => 'Missing Required Data.',
                'data'    => null
            ];
            return $response;
        }

        $subject_mapping =  SubjectMapping::where([
                                'curriculum_id' => $request->curriculum_id,
                                'department_id' => $request->department_id,
                                'level_id'  => $request->level_id,
                                'track_id'  => $request->track_id,
                                'term_type' => $request->term_type
                            ])->first();

        $response['status'] = 'success';

        if($subject_mapping) {
            $response['data']   = $subject_mapping;
        } else {
            $response['message']   = "No Subject Mapping.";
        }
        return $response;
    }
}
