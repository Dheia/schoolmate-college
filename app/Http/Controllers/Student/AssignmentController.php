<?php

namespace App\Http\Controllers\Student;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\Student\SubmittedAssignmentRequest as StoreRequest;
use App\Http\Requests\Student\SubmittedAssignmentRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

use App\Models\Assignment;
use App\Models\StudentSubmittedAssignment;
use App\Models\QuipperStudentAccount;
use App\Models\OnlineClassAttendance;

use Carbon\Carbon;

use App\Http\Controllers\Student\OnlineClassController;
/**
 * Class StudentSubmittedAssignmentCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class AssignmentController extends CrudController
{
    public $class_attendance    = null;

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\StudentSubmittedAssignment');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/online-class-assignments');
        $this->crud->setEntityNameStrings('Submitted Assignment', 'Student Submitted Assignment');

        if(request()->id)
        {
             \Redirect::to('dashboard');
            // abort(404, 'Missing Required Parameters.');
        }

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        // add asterisk for fields that are required in StudentSubmittedAssignmentRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        /*
        |--------------------------------------------------------------------------
        | Get Informations
        |--------------------------------------------------------------------------
        */
        if(request()->id)
        {
            $this->data['assignment_status']    =   'Not Yet Submitted';
            $onlineClassController  =   new OnlineClassController();

            $student                =   auth()->user()->student;
            $assignment             =   Assignment::with('class')->where('id', request()->id)->first();
            $student_section        =   $onlineClassController->studentSectionAssignment();
            // $my_classes             =   $onlineClassController->getOnlineClasses();
            $section_ids            =   collect($student_section)->pluck('section_id')->toArray();
            $quipperAccount         =   QuipperStudentAccount::where('student_id', $student->id)->first();

            if(!$assignment) {
                abort(403, 'Assignment Not Found');
            }
            if(!$assignment->class) {
                abort(403, 'Class Not Found.');
            }
            if(!in_array($assignment->class->section_id, $section_ids)){
                abort(403, 'Mismatch Class');
            }
            if(!in_array($assignment->class->term_type, collect($student_section)->pluck('term_type')->toArray())){
            abort(403, 'Mismatch Class');
            }
            if(!in_array($assignment->class->summer, collect($student_section)->pluck('summer')->toArray())){
                abort(403, 'Mismatch Class');
            }
            $class                  =   $onlineClassController->getOnlineClass($assignment->class->code);
            $submittedAssignments   =   StudentSubmittedAssignment::where('student_id', $student->id)
                                                            ->where('assignment_id', $assignment->id)
                                                            ->first();
            if($submittedAssignments){
                 $this->data['assignment_status']    =   $submittedAssignments->status;
            } 

            /*
            |--------------------------------------------------------------------------
            | FIELDS
            |--------------------------------------------------------------------------
            */
            $this->crud->addField([
                'label' => '',
                'name'  =>  'assignment_id',
                'type'  =>  'hidden',
                'value' =>  $assignment->id
            ]);

            if($assignment->type == 'submission')
            {
                // onlineClass.files has a field with a name "files" use to store in Database
                $this->crud->addField([
                    'name' => 'select_files',
                    'label' => 'Upload File',
                    'type' => 'onlineClass.files',
                    'upload' => true,
                    'disk' => 'uploads'
                ]);
                // $this->crud->addField([   // Upload
                //     'name' => 'file',
                //     'label' => 'Upload File',
                //     'type' => 'upload',
                //     'upload' => true,
                //     'disk' => 'uploads' // if you store files in the /public folder, please ommit this; if you store them in /storage or S3, please specify it;
                // ]);
            }

            if($assignment->type == 'essay')
            {
                $this->crud->addField([   // Upload
                    'name' => 'answer',
                    'label' => 'Answer',
                    'type' => 'ckeditor',
                     'extra_plugins' => ['oembed', 'widget', 'justify', 'grid', 'mathjax', 'autocomplete', 'tableresize', 'slideshow'],
                ]);

            }

            /*
            |--------------------------------------------------------------------------
            | CrudPanel Set Data
            |--------------------------------------------------------------------------
            */
            $this->data['class']                =   $class;
            $this->data['user']                =   $student;
            $this->data['student']              =   $student;
            $this->data['assignment']           =   $assignment;
            $this->data['quipperAccount']       =   $quipperAccount;
            $this->data['submittedAssignments'] =   $submittedAssignments;
            $this->data['class_attendance']     =   OnlineClassAttendance::getStudentAttendanceToday($class->id, $student->id);

            /*
            |--------------------------------------------------------------------------
            | CrudPanel Set View
            |--------------------------------------------------------------------------
            */
            $this->crud->setListView('student.assignment.show-assignment');
            $this->crud->setCreateView('student.assignment.show-assignment');
            $this->crud->setEditView('student.assignment.show-assignment');
        }
        else
        {
            $onlineClassController  =   new OnlineClassController();

            $student                =   auth()->user()->student;
            $student_section        =   $onlineClassController->studentSectionAssignment();
            $my_classes             =   $onlineClassController->getOnlineClasses();
            $section_ids            =   collect($student_section)->pluck('section_id')->toArray();
            $quipperAccount         =   QuipperStudentAccount::where('student_id', $student->id)->first();

            $studentsAssignments    =    StudentSubmittedAssignment::where('student_id', $student->id)->get();

            $assignments            =   Assignment::with('class')
                                            ->whereIn('online_class_id', $my_classes ? $my_classes->pluck('id') : [])
                                            ->whereNotIn('id', $studentsAssignments->pluck('assignment_id'))
                                            ->orderBy('due_date', 'DESC')
                                            ->get();
            $submittedAssignments   =   StudentSubmittedAssignment::where('student_id', $student->id)
                                                ->whereIn('assignment_id', $assignments->pluck('id'))
                                                ->get();

            /*
            |--------------------------------------------------------------------------
            | CrudPanel Set Data
            |--------------------------------------------------------------------------
            */
            $this->data['my_classes']           =   $my_classes;
            $this->data['student']              =   $student;
            $this->data['assignments']          =   $assignments;
            $this->data['quipperAccount']       =   $quipperAccount;
            $this->data['submittedAssignments'] =   $submittedAssignments;

            /*
            |--------------------------------------------------------------------------
            | CrudPanel Set View
            |--------------------------------------------------------------------------
            */
            $this->crud->setListView('student.assignment.list');
            $this->crud->setCreateView('student.assignment.list');
            $this->crud->setEditView('student.assignment.list');
        }
    }

    public function store(StoreRequest $request)
    {
        $assignment = Assignment::where('id', request()->id)->first();
        $rubrics = [];
        foreach (json_decode($assignment->rubrics) as $key => $value) {
            $rubrics[] = [
                'name' => $value->name,
                'points' => $value->points,
                'score' => '',
                'comment' => ''
            ];
        }
        $request->request->set('assignment_id', request()->id);
        $request->request->set('student_id', auth()->user()->student->id);
        $request->request->set('rubrics', json_encode($rubrics));
        $request->request->set('status', 'Submitted');
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

    public function getStudentAssignments()
    {
        $this->student = $student = auth()->user()->student;
        $onlineClassController  =   new OnlineClassController();
        $classes                =   $onlineClassController->getOnlineClasses();

        $assignments            =   Assignment::with('employee')
                                                ->whereIn('online_class_id', $classes->pluck('id'))
                                                ->notArchive()
                                                ->active()
                                                ->get();
        // Get All Student Submitted Assignments
        $submittedAssignments   =   StudentSubmittedAssignment::where('student_id', $student->id)
                                                    ->whereIn('assignment_id', $assignments->pluck('id'))->get();

        $this->data['user']                 =   $student;
        $this->data['student']              =   $student;
        $this->data['my_classes']           =   $classes;
        $this->data['assignments']          =   $assignments;
        $this->data['submittedAssignments'] =   $submittedAssignments; 
        $this->data['class_attendance']     =   OnlineClassAttendance::getStudentAttendanceToday($class->id, $student->id);

        return view('student.assignment.list', $this->data);
    }

    public function getClassAssignments($class_code)
    {
        $onlineClassController  =   new OnlineClassController();

        $student                =   auth()->user()->student;
        $student_section        =   $onlineClassController->studentSectionAssignment();
        $class                  =   $onlineClassController->getOnlineClass($class_code);
        // $my_classes             =   $onlineClassController->getOnlineClasses();
        $section_ids            =   collect($student_section)->pluck('section_id')->toArray();
        $quipperAccount         =   QuipperStudentAccount::where('student_id', $student->id)->first();

        if(!$class) {
            abort(403, 'Mismatch Class');
        }
        if(!in_array($class->section_id, $section_ids)){
            abort(403, 'Mismatch Class');
        }
        if(!in_array($class->term_type, collect($student_section)->pluck('term_type')->toArray())){
            abort(403, 'Mismatch Class');
        }
        if(!in_array($class->summer, collect($student_section)->pluck('summer')->toArray())){
            abort(403, 'Mismatch Class');
        }
        $assignments            =   Assignment::where('online_class_id', $class->id)->orderBy('due_date', 'DESC')->get();
        $submittedAssignments   =   StudentSubmittedAssignment::where('student_id', $student->id)
                                                        ->whereIn('assignment_id', $assignments->pluck('id'))
                                                        ->orderBy('created_at', 'DESC')
                                                        ->get();

        $class_attendance =   OnlineClassAttendance::getStudentAttendanceToday($class->id, $student->id);

        return view('student.assignment.class-assignments')
                    ->with('class', $class)
                    ->with('user', $student)
                    ->with('student', $student)
                    ->with('assignments', $assignments)
                    ->with('quipperAccount', $quipperAccount)
                    ->with('class_attendance', $class_attendance)
                    ->with('submittedAssignments', $submittedAssignments);

    }
}
