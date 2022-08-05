<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\TeacherAssignmentRequest as StoreRequest;
use App\Http\Requests\TeacherAssignmentRequest as UpdateRequest;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\SchoolYear;
use App\Models\YearManagement;
use App\Models\TrackManagement;
use App\Models\TeacherSubject;
use App\Models\Employee;

/**
 * Class TeacherAssignmentCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class TeacherAssignmentCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\TeacherAssignment');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/teacher-assignment');
        $this->crud->setEntityNameStrings('Teacher Assignment', 'Teacher Assignments');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in TeacherAssignmentRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        // $this->crud->setCreateView('teacherAssignment.create');

        // // SET THE DATA IF ACTION METHOD IS CREATE
        // $actionMethod               = $this->crud->getActionMethod();
        // $this->data['actionMethod'] = $actionMethod;

        if(!backpack_auth()->user()->hasRole('School Head')) {
            $this->crud->denyAccess('list');
        }

        $this->crud->denyAccess(['update', 'create', 'delete']);

        $this->crud->addColumn([
            'label' => 'Employee No.',
            'type' => 'text',
            'name' => 'employee_id',
            'prefix' => config('settings.schoolabbr') . ' - '
        ]);

        $this->crud->addColumn([
            'label' => 'Full Name',
            'type' => 'text',
            'name' => 'full_name',
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('employee', function ($q) use ($column, $searchTerm) {
                    $q->where('firstname', 'like', '%'.$searchTerm.'%')
                        ->orWhere('lastname', 'like', '%'.$searchTerm.'%')
                        ->orWhere('employee_id', 'like', '%'.$searchTerm.'%');
                });
            },
        ]);

        $this->crud->addButtonFromView('line', 'assign_subject', 'teacherAssignment.assign_subject', 'beginning');
        $this->crud->addClause('teachingPersonnel');
        $this->crud->addButtonFromView('line', 'Print', 'teacherAssignment.print', 'end');
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

    public function destroy ($id)
    {
        $model = $this->crud->model::findOrFail($id);
        $model->delete();
    }

    public function getSubjects ()
    {
        $subjects = Subject::where('studentnumber', 'LIKE', '%' . $request->phrase . '%')
                            // ->where('level_id', $request->level_id)
                            ->orWhere('firstname', 'LIKE', '%' . $request->phrase . '%')
                            ->orWhere('middlename', 'LIKE', '%' . $request->phrase . '%')
                            ->orWhere('lastname', 'LIKE', '%' . $request->phrase . '%')
                            ->select('id', 'studentnumber', 'gender', 'schoolyear', 'level_id', 'firstname', 'middlename', 'lastname')
                            ->with('schoolYear')
                            ->with('yearManagement')
                            ->take(5)->get();

        return response()->json($subjects);
    }

    public function searchStudent (Request $request)
    {
        $students = Student::join('enrollments', function ($join) use ($request) {
                                $join->on('students.studentnumber', 'enrollments.studentnumber')
                                    ->where([
                                        'enrollments.school_year_id'    => SchoolYear::active()->first()->id,
                                        // 'enrollments.level_id'          => $request->level_id
                                    ]);
                            })
                            ->where('students.studentnumber', 'LIKE', '%' . $request->phrase . '%')
                            ->orWhere('students.firstname', 'LIKE', '%' . $request->phrase . '%')
                            ->orWhere('students.middlename', 'LIKE', '%' . $request->phrase . '%')
                            ->orWhere('students.lastname', 'LIKE', '%' . $request->phrase . '%')
                            ->select('students.id', 'students.studentnumber', 'students.gender', 'students.schoolyear', 'students.level_id', 'students.firstname', 'students.middlename', 'students.lastname')
                            ->with('schoolYear')
                            ->with('yearManagement')
                            ->take(5)
                            ->get();

        return response()->json($students);
    }
    public function print ($id)
    {
        $employee_datas  = Employee::Where('id',$id)->first();
        $schoollogo      = config('settings.schoollogo') ? (string)\Image::make(config('settings.schoollogo'))->encode('data-url') : null;
        $schoolmate_logo = (string)\Image::make('images/schoolmate_logo.jpg')->encode('data-url');
        
        $teacher_subjects = TeacherSubject::with('subject')
                                            ->with('section')
                                            ->with('schoolYear')
                                            ->where('teacher_id',$id)
                                            ->where('school_year_id',SchoolYear::where('isActive',1)->pluck('id')->first())
                                            ->get();
       
        return view('teacherAssignment.generateReport', compact('teacher_subjects','employee_datas','schoollogo','schoolmate_logo'));

    }
}
