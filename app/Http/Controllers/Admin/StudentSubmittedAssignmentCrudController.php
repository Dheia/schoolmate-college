<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\StudentSubmittedAssignmentRequest as StoreRequest;
use App\Http\Requests\StudentSubmittedAssignmentRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

use App\Models\StudentSubmittedAssignment;
use App\Models\OnlineClass;
use App\Models\Assignment;
use App\Models\Student;

/**
 * Class StudentSubmittedAssignmentCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class StudentSubmittedAssignmentCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\StudentSubmittedAssignment');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/online-class/submitted-assignment');
        $this->crud->setEntityNameStrings('Submitted Assignment', 'Student Submitted Assignment');

        // Setters
        $this->crud->setTitle('Submitted Assignment', 'edit'); // set the Title for the update action

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();
        $this->data['user']           =   backpack_auth()->user();
        $submitted_id = request()->submitted_assignment;
        $this->data['assignment'] = Assignment::where('id', request()->assignment_id)->first();
        // dd($columns);
        // EDIT
        $submitted_id = \Route::current()->parameter('submitted_assignment');
        if($submitted_id)
        {
            $studentnumber  =   request()->studentnumber;
            $assignment_id  =   request()->assignment_id;
            $user           =   backpack_auth()->user();
            $class          =   self::getClass(request()->class_code);
            $student        =   Student::with('submittedAssignments')->where('studentnumber', $studentnumber)->first();
            if(!$class)
            { 
                abort(404, 'Unknown Class.');
            }

            // Check If Employee ID is Equal To Class Employee ID
            if(!backpack_user()->hasRole('School Head'))
            {
                if($class->teacher_id != backpack_auth()->user()->employee_id)
                { 
                    abort(403, 'Unauthorized Access.');
                }
            }
            $assignment     =   Assignment::where('id', $assignment_id)
                                    ->where('online_class_id', $class->id)
                                    ->notArchive()
                                    ->active()
                                    ->first();
            if(!$student){
                abort(404, 'Student not found.');
            }
            if(!$assignment){
                abort(404, 'Assignment not found.');
            }
            // Get Submitted Assignment
            $submittedAssignments   =   $student->submittedAssignments->where('assignment_id', $assignment->id)->first();


            $this->data['class'] = $class;
            $this->data['student'] = $student;
            $this->data['assignment'] = $assignment;
            $this->data['submittedAssignments'] = $submittedAssignments;

            $this->crud->addField(
            [
                'name' => 'rubrics',
                'label' => '<h3 class="text-center" style="margin-bottom: 20px;">Rubrics</h3>',
                'type' => 'subjectMapping.child_subjects',
                'entity_singular' => 'Rubric', // used on the "Add X" button
                'columns' => [
                    [
                        'label'      => 'Name',
                        'type'       => 'child_text',
                        'name'       => 'name',
                        'attributes' => [ 'readonly' => 'readonly', 'required' => 'required' ],
                    ],
                    [
                        'label'      => 'Points',
                        'type'       => 'child_text',
                        'name'       => 'points',
                        'attributes' => [ 'id' => 'points', 'readonly' => 'readonly' ],
                    ],
                   
                    [
                        'label'      => 'Score',
                        'type'       => 'child_number',
                        'name'       => 'score',

                        'attributes' => [ 'id' => 'score', 'required' => 'required' ],
                    ],
                    [
                        'label'      => 'Comment',
                        'type'       => 'child_text',
                        'name'       => 'comment',
                        'attributes' => [ 'id' => 'comment', 'required' => 'required'],
                    ],
                ],
                'min' => $this->data['assignment']->total_rubric,
                'max'  => $this->data['assignment']->total_rubric
        ], 'update');
            $this->crud->setEditView('onlineClass.assignment.student-assignment');
        }
        // add asterisk for fields that are required in StudentSubmittedAssignmentRequest
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
        $request->request->set('status', 'Scored');
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function showSubmittedAssignment($assignment_id, $studentnumber)
    {
        $crud           =   $this->crud;
        $user           =   backpack_auth()->user();
        $class          =   self::getClass(request()->class_code);
        $student        =   Student::with('submittedAssignments')->where('studentnumber', $studentnumber)->first();
        $assignment     =   Assignment::where('id', $assignment_id)
                                ->where('online_class_id', $class->id)
                                ->notArchive()
                                ->active()
                                ->first();
        if(!$student){
            \Alert::warning('Student not found.')->flash();
            return redirect()->back();
        }
        if(!$assignment){
            \Alert::warning('Assignment not found.')->flash();
            return redirect()->back();
        }
        // Get Submitted Assignment
        $submittedAssignments   =   $student->submittedAssignments->where('assignment_id', $assignment->id)->first();

        $entry = $submittedAssignments;

        return view('onlineClass.assignment.student-assignment', compact(['submittedAssignments', 'class', 'assignment', 'user', 'crud', 'student', 'entry']));
    }

    public function getClass($class_code)
    {
        $class  =   OnlineClass::with([
                            'subject',
                            'section',
                            'teacher',
                            'activeStudentSectionAssignment'
                        ])
                        ->where('code', $class_code)
                        ->activeSchoolYear()
                        ->notArchive()
                        ->active()
                        ->first();

        if(!$class)
        { 
            \Alert::warning("Error, Class Code not Found.")->flash();
            return redirect()->back();
        }

        // Check If Employee ID is Equal To Class Employee ID
        if(!backpack_user()->hasRole('School Head'))
        {
            if($class->teacher_id != backpack_auth()->user()->employee_id)
            { 
                \Alert::warning("Error, Unauthorized Access.")->flash();
                return redirect()->back();
            }
        }
        
        return $class;
    }
}
