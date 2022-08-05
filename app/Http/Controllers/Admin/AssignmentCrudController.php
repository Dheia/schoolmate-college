<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\AssignmentRequest as StoreRequest;
use App\Http\Requests\AssignmentRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

use App\Models\Assignment;
use App\Models\SchoolYear;
use App\Models\OnlineClass;
use App\Models\Student;

use App\Models\OnlineClassAttendance;

/**
 * Class AssignmentCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class AssignmentCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | Check If User is Tag as Employee
        |--------------------------------------------------------------------------
        */
        if(backpack_auth()->user()->employee_id === null) {
            abort(403, 'Your User Account Is Not Yet Tag As Employee');
        }

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Assignment');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/online-class/assignment');
        $this->crud->setEntityNameStrings('assignment', 'assignments');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();
        $this->crud->allowAccess('show');

        $this->crud->removeButton('show');
        $this->crud->removeButton('update');
        $this->crud->removeButton('delete');

        $this->crud->addButtonFromView('line', 'assignment.view', 'assignment.view', 'first');
        $this->crud->addButtonFromView('line', 'assignment.update', 'assignment.update', 'end');
        $this->crud->addButtonFromView('line', 'assignment.delete', 'assignment.delete', 'first');

        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->crud->setCreateView('onlineClass.assignment.create');

        $this->data['user']        =   backpack_auth()->user();

        if(!request()->class_code)
        {
            $my_classes     =   $this->getMyClasses();
            $this->data['assignments']  =   Assignment::with('class', 'employee')->whereIn('online_class_id', collect($my_classes)->pluck('id'))->get();
            $this->data['my_classes']   =   $my_classes;
            $this->crud->setListView('onlineClass.assignment.list');
        }
        /*
        |--------------------------------------------------------------------------
        | Fields
        |--------------------------------------------------------------------------
        */
        if(!request()->class_code){
            if(backpack_user()->hasRole('School Head')){
                $this->crud->addField([       // Select2Multiple = n-n relationship (with pivot table)
                    'label' => "Select Classes",
                    'type' => 'select2_multiple',
                    'name' => 'online_class_id', // the method that defines the relationship in your Model
                    'entity' => 'class', // the method that defines the relationship in your Model
                    'attribute' => 'name', // foreign key attribute that is shown to user
                    'model' => "App\Models\OnlineClass", // foreign key model
                    'options'   => (function ($query) {
                        return $query->orderBy('name', 'ASC')
                                    ->where('school_year_id', SchoolYear::active()->first()->id)
                                    ->active()
                                    ->notArchive()
                                    ->get();
                    }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
                ], 'create');
            }
            else{
                $this->crud->addField([       // Select2Multiple = n-n relationship (with pivot table)
                    'label' => "Select Classes",
                    'type' => 'select2_multiple',
                    'name' => 'online_class_id', // the method that defines the relationship in your Model
                    'entity' => 'class', // the method that defines the relationship in your Model
                    'attribute' => 'name', // foreign key attribute that is shown to user
                    'model' => "App\Models\OnlineClass", // foreign key model
                    'options'   => (function ($query) {
                        return $query->orderBy('name', 'ASC')
                                    ->where('teacher_id', backpack_auth()->user()->employee_id)
                                    ->where('school_year_id', SchoolYear::active()->first()->id)
                                    ->active()
                                    ->notArchive()
                                    ->get();
                    }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
                ], 'create');
            }
        }
        else {
            $class = $this->getClass(request()->class_code);
            $this->data['class']            =   $class;
            $this->data['assignments']      =   Assignment::where('online_class_id', $class->id)->get();
            $this->data['class_attendance'] =   OnlineClassAttendance::getEmployeeAttendanceToday($class->id, backpack_user()->employee_id);
            $this->crud->setListView('onlineClass.assignment.class-assignments');
            $this->crud->setCreateView('onlineClass.assignment.class-create');
        }

        $this->crud->addField([ // select_from_array
            'name'  => 'type',
            'label' => 'Type <br><small style="font-weight: 100 !important; color: red;">Note: Students will be required to upload a file for submission.</small>',
            'type'  => 'select_from_array',
            'options' => ['submission' => 'Submission', 'essay' => 'Essay'],
            'default' => 'submission',
            'allows_null' => false,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
        ]);

        $this->crud->addField([   // Date
            'name' => 'due_date',
            'label' => '<br>Due Date',
            'type' => 'date',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
        ]);

        $this->crud->addField([ // select_from_array
            'name'  => 'title',
            'label' => "Title",
            'type'  => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ],
            // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
        ]);

        $this->crud->addField([ // select_from_array
            'name'  => 'instructions',
            'label' => "Instructions",
            'type'  => 'ckeditor',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ],
            // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
        ]);

        $this->crud->addField([ // select_from_array
            'name' => 'rubrics',
            'label' => 'Rubrics',
            'type' => 'table',
            'entity_singular' => 'criteria', // used on the "Add X" button
            'columns' => [
                'name' => 'Name',
                'points' => 'Points'
            ],
            'max' => 5, // maximum rows allowed in the table
            'min' => 5, // minimum rows allowed in the table
        ]);


        $this->crud->addField([
            'label' => 'Total',
            'type'  => 'text',
            'name'  => 'total',
            'attributes' => [
               'readonly'=>'readonly',
               'disabled'=>'disabled',
             ],
             'wrapperAttributes' => [
               'class' => 'form-group col-md-3'
             ]
        ]);

        $this->crud->addField([
            'label' => 'Employee',
            'type'  => 'hidden',
            'name'  => 'employee_id',
            'value' => backpack_auth()->user()->employee_id
        ]);

        $this->crud->addField([
            'label' => '',
            'type'  => 'assignment.assignment_script',
            'name'  => 'script'
        ]);

        /*
        |--------------------------------------------------------------------------
        | Columns
        |--------------------------------------------------------------------------
        */
        $this->crud->addColumn([
           // 1-n relationship
           'label' => "Class", // Table column heading
           'type' => "select",
           'name' => 'online_class_id', // the column that contains the ID of that connected entity;
           'entity' => 'class', // the method that defines the relationship in your Model
           'attribute' => "name", // foreign key attribute that is shown to user
           'model' => "App\Models\OnlineClass", // foreign key model
        ]);
        $this->crud->addColumn([
            'name'  => 'title',
            'label' => "Title",
            'type'  => 'text',
        ]);

        $this->crud->addColumn([
            'name'  => 'submitted',
            'label' => "Submitted",
            'type'  => 'text'
        ]);

        $this->crud->addColumn([
            'name' => "due_date", // The db column name
            'label' => "Due Date", // Table column heading
            'type' => "date",
            'format' => 'MMMM D, Y', // use something else than the base.default_date_format config value
        ]);

        $this->crud->addColumn([
            'name'  => 'status',
            'label' => "Status",
            'type'  => 'text'
        ]);

        if(!backpack_user()->hasRole('School Head'))
        {
            $this->crud->addClause('where', 'employee_id', '=', backpack_auth()->user()->employee_id);
        }
    }

    public function store(StoreRequest $request)
    {
        if(!request()->class_code)
        {
            $classes = OnlineClass::whereIn('id', $request->online_class_id)->get();
            foreach ($classes as $key => $class) {
                $assignment = new Assignment();
                $assignment->online_class_id    =   $class->id;
                $assignment->type               =   $request->type;
                $assignment->title              =   $request->title;
                $assignment->instructions       =   $request->instructions;
                $assignment->rubrics            =   $request->rubrics;
                $assignment->due_date           =   $request->due_date;
                $assignment->employee_id        =   backpack_auth()->user()->employee_id;
                $assignment->save();
            }
            \Alert::success("Assignment Added Successfully!")->flash();
            return redirect('/admin/online-class/assignment');
        }
        else {
            $class = $this->getClass(request()->class_code);
        }
        $request->request->set('online_class_id', $class->id);
        $request->request->set('employee_id', backpack_auth()->user()->employee_id);
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

    public function show($id)
    {
        $this->data['assignment'] = Assignment::where('id' , $id)->where('online_class_id', $this->data['class']->id)
                                        ->first();
        if(!$this->data['assignment'])
        { 
            \Alert::warning('Assignment not found.')->flash();
            return redirect('admin/teacher-online-class');
        }
        if(!backpack_user()->hasRole('School Head'))
        {
            if($this->data['class']->teacher_id != backpack_auth()->user()->employee_id)
            {
                \Alert::warning('Unauthorized Access.')->flash();
                return redirect('admin/teacher-online-class');
            }
        }
        $studentnumbers     =   json_decode($this->data['class']->studentSectionAssignment->students);
        $this->data['crud'] = $this->crud;
        $this->data['students'] = Student::whereIn('studentnumber', $studentnumbers)->with('submittedAssignments')->get();
        return view('onlineClass.assignment.show', $this->data);
    }

    public function showClassAssignment($class_code)
    {
        $crud = $this->crud;
        $class = self::getClass($class_code);
        $assignment = Assignment::where('online_class_id', $class->id)->first();
        $total_points = 0;
        if(count(json_decode($assignment->rubrics))>0)
        {
            foreach (json_decode($assignment->rubrics) as $key => $value) {
                if($value->name && $value->points)
                {
                    $total_points += $value->points;
                }
            }
        }
        dd($total_points);
        
        return view('onlineClass.assignment.show', compact(['crud', 'class']));
    }

    public function getMyClasses()
    {
        $classes = [];
        if(backpack_auth()->user()->hasRole('School Head')){
            $classes    =   OnlineClass::with([
                                    'section', 
                                    'teacher', 
                                    'subject',
                                    'course',
                                    'activeStudentSectionAssignment'
                                ])
                                ->orderBy('online_classes.name')
                                ->activeSchoolYear()
                                ->notArchive()
                                ->active()
                                ->get();
            return $classes;
        }
        else if(backpack_user()->hasRole('Teacher')){
            $classes    =   OnlineClass::with([
                                    'section', 
                                    'teacher', 
                                    'subject',
                                    'course',
                                    'activeStudentSectionAssignment'
                                ])
                                ->orderBy('online_classes.name')
                                ->where('teacher_id', backpack_auth()->user()->employee_id)
                                ->activeSchoolYear()
                                ->notArchive()
                                ->active()
                                ->get();
            return $classes;
        }
        return $classes;
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
            abort(403, 'Class Code not found.'); 
        }

        // Check If Employee ID is Equal To Class Employee ID
        if(!backpack_user()->hasRole('School Head'))
        {
            if($class->teacher_id != backpack_auth()->user()->employee_id)
            { 
                \Alert::warning("Error, Unauthorized Access.")->flash();
                abort(403, 'Unauthorized Access.'); 
            }
        }
        
        return $class;
    }
}
