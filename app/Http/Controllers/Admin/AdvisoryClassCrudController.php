<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
// use App\Http\Requests\AdvisoryClassRequest as StoreRequest;
// use App\Http\Requests\AdvisoryClassRequest as UpdateRequest;
use App\Http\Requests\StudentSectionAssignmentRequest as StoreRequest;
use App\Http\Requests\StudentSectionAssignmentRequest as UpdateRequest;

use Backpack\CRUD\CrudPanel;

use App\Models\SchoolYear;
use App\Models\EncodeGrade;
use App\Models\SubjectMapping;
use App\Models\SubjectManagement;
use App\Models\SectionManagement;
use App\Models\StudentSectionAssignment;
use App\Models\Employee;

/**
 * Class AdvisoryClassCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class AdvisoryClassCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        // $this->crud->setModel('App\Models\AdvisoryClass');
        $this->crud->setModel('App\Models\StudentSectionAssignment');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/advisory-class');
        $this->crud->setEntityNameStrings('Advisory Class', 'Advisory Classes');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */ 
        $this->crud->denyAccess(['create', 'update', 'delete']);

        // if(!backpack_user()->employee)
        // { 
        //     abort(403, 'Your User Account Is Not Yet Tag As Employee');
        // }

        if(!backpack_auth()->user()->hasRole('School Head'))
        {
            $this->crud->addClause('where', 'employee_id', backpack_user()->employee_id);
        }
        else
        {
            
            $this->crud->addColumn([  // Select2
               'label' => "Adviser",
               'type' => 'select',
               'name' => 'employee_id', // the db column for the foreign key
               'entity' => 'employee', // the method that defines the relationship in your Model
               'attribute' => 'full_name', // foreign key attribute that is shown to user
               'model' => "App\Models\Employee", // foreign key model
            ]);
        }

        // COLUMN

        $this->crud->addColumn([
            'label'             => 'Class Code',
            'type'              => 'text',
            'name'              => 'class_code',
        ]);

        $this->crud->addColumn([
            'label'             => 'Level',
            'type'              => 'text',
            'name'              => 'level',
        ]);

        $this->crud->addColumn([
            'label'             => 'Department',
            'type'              => 'text',
            'name'              => 'department',
        ]);

        $this->crud->addColumn([
            'label'             => 'Section',
            'type'              => 'select',
            'name'              => 'section_id',
            'attribute'         => 'name',
            'entity'            => 'section',
            'model'             => 'App\Models\SectionManagement'
        ]);

        $this->crud->addColumn([
            'label'             => 'Term',
            'type'              => 'text',
            'name'              => 'term_type'
        ]);

        $this->crud->addColumn([
            'label'             => 'School Year',
            'type'              => 'select',
            'name'              => 'school_year_id',
            'attribute'         => 'schoolYear',
            'entity'            => 'schoolYear',
            'model'             => 'App\Models\SchoolYear'
        ]);

        $this->crud->addColumn([
            'label'             => 'Track',
            'type'              => 'text',
            'name'              => 'track',
            'attribute'         => 'year',
        ]);

        $this->crud->addColumn([
            'label'             => 'Total Students',
            'type'              => 'text',
            'name'              => 'total_students_per_section',
        ]);

        // FILTERS
        $this->crud->addFilter([ // select2 filter
          'name' => 'school_year_id',
          'type' => 'select2',
          'label'=> 'School Year'
        ], function() {
            return SchoolYear::all()->pluck('schoolYear', 'id')->toArray();
        }, function($value) { // if the filter is active
            $this->crud->addClause('where', 'school_year_id', $value);
        });

        $this->crud->addFilter([ // select2 filter
          'name' => 'section_id',
          'type' => 'select2',
          'label'=> 'Section'
        ], function() {
            return SectionManagement::all()->pluck('name', 'id')->toArray();
        }, function($value) { // if the filter is active
            $this->crud->addClause('where', 'section_id', $value);
        });

        $this->crud->allowAccess('section grades');
        $this->crud->addButtonFromView('line', 'Section Grades', 'advisoryClass.section_grades', 'end');
        $this->crud->addButtonFromView('line', 'Print', 'advisoryClass.print', 'end');

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        // add asterisk for fields that are required in AdvisoryClassRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
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

    public function getGrades($id)
    {
        $this->data['crud'] = $this->crud;
        $student_section_assignment = StudentSectionAssignment::findOrFail($id);

        if(!backpack_auth()->user()->hasRole('School Head'))
        {
            if($student_section_assignment->employee_id != backpack_user()->employee_id)
            {
                abort(403, 'Unauthorized access - you do not have the necessary permissions to see this page.');
            }
        }
        $this->data['student_section_assignment']       =   $student_section_assignment;
        $this->data['section']      =   $section        =   $student_section_assignment->sectionWithTrashed;
        $this->data['level']        =   $level          =   $student_section_assignment->sectionWithTrashed ? $student_section_assignment->sectionWithTrashed->level : null;

        $this->data['department']   =   $department     =   $this->data['level'] ? $this->data['level']->departmentWithTrashed : null;
        $this->data['schoolYear']   =   $schoolYear     =   $student_section_assignment->schoolYear;
        $this->data['students']     =   $students       =   $student_section_assignment->all_students;

        // Abort If There's Missing Data
        abort_if(!$section, 404, 'Section not found.');
        abort_if(!$level, 404, 'Level not found.');
        abort_if(!$department, 404, 'Department not found.');
        abort_if(!$schoolYear, 404, 'School Year not found.');

        $subject_mappings   =   SubjectMapping::where('curriculum_id', $student_section_assignment->curriculum_id)
                                    ->where('department_id', $department->id)
                                    ->where('level_id', $level->id)
                                    ->where('track_id', $section->track_id)
                                    ->where('term_type', $student_section_assignment->term_type)
                                    ->get();
        $subject_mapping    =   $subject_mappings->last();
        $subjects   =   [];

        if($subject_mapping['subjects'] ?? '')
        {
            foreach($subject_mapping['subjects'] as $subject)
            {
                $subject = SubjectManagement::withTrashed()->find($subject->subject_code);
                if($subject)
                {
                    $encodeGrade = EncodeGrade::where('school_year_id', $schoolYear->id)
                                            ->where('term_type', $student_section_assignment->term_type)
                                            ->where('subject_id', $subject->id)
                                            ->where('section_id', $section->id)
                                            ->where('submitted', 1)
                                            ->get();

                    $subject->EncodeGrade = $encodeGrade;
                    $subjects[] = $subject;
                }
            }
        }
        // dd($subjects);
        $this->data['subjects'] = $subjects;
        $this->data['periods']  = $department->periods;
        return view('studentSectionAssignment.sectionGrades', $this->data);
    }

    public function getStudentGrades($id)
    {
        $studentGrades = [];
        $ssa = StudentSectionAssignment::findOrFail($id);

        $subjects = request()->subjects;
        $school_year_id = $ssa->school_year_id; 
        $studentnumber = request()->studentnumber; 
        $term_type = $ssa->term_type; 
        $section_id = $ssa->section_id; 
        $period_id = request()->period_id;
        $encodeGrades = [];
        if($subjects)
        {
            if(count($subjects)>0)
            {
                foreach($subjects as $subject)
                {
                    $encodeGrades[] = EncodeGrade::where('school_year_id', $school_year_id)
                                                ->where('term_type', $term_type)
                                                ->where('subject_id', $subject)
                                                ->where('section_id', $section_id)
                                                ->where('period_id', $period_id)
                                                // ->where('submitted', 1)
                                                // ->whereRaw("json_contains('rows', '$studentnumber', '$[0].studentnumber')")
                                                ->first();
                   // if($encodeGrade) {                             
                   //      $grades = $encodeGrade->rows;
                   //      $grade  =   collect($grades)->filter(function ($value, $key) use ($studentnumber) { 
                   //                      return $value->studentnumber == $studentnumber; 
                   //                  });

                   //      $studentGrades[] = [
                   //          'studentnumber' => $studentnumber,
                   //          'term_type' => $term_type,
                   //          'subject_id' => $subject,
                   //          'initial'   =>  $grade[0]->initial_grade
                   //      ];
                   //  }
                }
            }
        }
        // dd(collect($encodeGrades)->filter())
        // dd(array_filter($encodeGrades));
        return response()->json(array_values(array_filter($encodeGrades)));
    }
    public function print($id){
        $this->data["student_section"] = StudentSectionAssignment::where('id', $id)->with(['section' => function ($q) { $q->with('level'); }])->first();
        $this->data["crud"] = $this->crud;
        $this->data['students'] = collect($this->data['student_section']->all_students)->sortBy('full_name')->groupBy('gender');
        // $pdf = \App::make('dompdf.wrapper');
        // $pdf->setPaper(array(0, 0, 612, 936), 'portrait');

        // $pdf->loadHTML( view('studentSectionAssignment.print', $this->data) );
        // return $pdf->stream();
        $schoollogo      = config('settings.schoollogo') ? (string)\Image::make(config('settings.schoollogo'))->encode('data-url') : null;
        $schoolmate_logo = (string)\Image::make('images/schoolmate_logo.jpg')->encode('data-url');

        return view('advisoryClass.generateReport',$this->data,compact('schoollogo','schoolmate_logo'));

    }
}
