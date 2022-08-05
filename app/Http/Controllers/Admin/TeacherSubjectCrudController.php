<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\TeacherSubjectRequest as StoreRequest;
use App\Http\Requests\TeacherSubjectRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

use Symfony\Component\HttpFoundation\Request;

// MODELS
use App\Models\YearManagement;
use App\Models\Employee;
use App\Models\SubjectMapping;
use App\Models\SectionManagement;
use App\Models\SubjectManagement;
use App\Models\TrackManagement;
use App\Models\SchoolYear;
use App\Models\User;
use App\Models\TeacherSubject;
use App\Models\TermManagement;
use App\Models\OnlineClass;
use App\Models\Department;

/**
 * Class TeacherSubjectCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class TeacherSubjectCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | Get The Teacher Information (teacher_id: required)
        |--------------------------------------------------------------------------
        */
        if(!request()->has('teacher_id')) {
            \Alert::warning("Missing Required Parameters!")->flash();
            abort(403);
        } else {
            
            if(backpack_auth()->user()->employee_id === null) {
                abort(403, 'Your User Account Is Not Yet Tag As Employee');
            }
            
            if(!backpack_auth()->user()->hasRole('School Head')) {
                if(backpack_auth()->user()->employee_id != request()->teacher_id) {
                    abort(403, 'Mismatch User');
                }
            }
            
            // $isExist = User::where('id', request()->get('teacher_id'))->exists();

            // if(!$isExist) {
            //     \Alert::warning("Teacher Not Found")->flash();
            //     abort(404);
            // }
        }
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\TeacherSubject');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/teacher-subject');
        $this->crud->setEntityNameStrings('Subject', 'Subjects');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        // add asterisk for fields that are required in TeacherSubjectRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        // Views
        $this->crud->setListView('teacherSubject.list');
        $this->crud->setCreateView('teacherSubject.create');
        $this->crud->setEditView('teacherSubject.edit');

        // Buttons
        // Add Transfer Class Button If School Head
        if(backpack_auth()->user()->hasRole('School Head'))
        {
            $this->crud->allowAccess('transfer_class');
            $this->crud->addButtonFromView('line', 'transfer_class', 'teacherSubject.transfer_class', 'beginning');
        }
        // Add Online Class Button
        $this->crud->allowAccess('create_online_class');
        $this->crud->addButtonFromView('line', 'create_online_class', 'teacherSubject.create_online_class', 'ending');

        // Override Edit and Delete Button
        $this->crud->addButtonFromView('line', 'update', 'teacherSubject.edit', 'ending');
        $this->crud->addButtonFromView('line', 'delete', 'teacherSubject.delete', 'ending');

        // Remove Columns and Fields
        $this->crud->removeColumns(['teacher_id']);
        $this->crud->removeFields(['teacher_id']);

        // Employees is Use In Transfer Class
        $this->data['employees']   = Employee::whereIn('type', array('Teaching Personnel', 'Non-Teaching/Teaching'))
                                        ->orderBy('lastname', 'ASC')
                                        ->orderBy('firstname', 'ASC')
                                        ->orderBy('middlename', 'ASC')
                                        ->get();
        $this->data['teacher'] = User::where('employee_id', request()->get('teacher_id'))->first();

        if(!$this->data['teacher'])
        {
            \Alert::warning("Teacher Not Found")->flash();
             abort(403, 'Your User Account Is Not Yet Tag As Employee');
        }

        // Check If Selected User Account Is Tag As Employee 
        if(!$this->data['teacher']->employee)
        {
            \Alert::warning("Employee Not Found")->flash();
            abort(403, 'Your User Account Is Not Yet Tag As Employee');
        }

        if(!$this->data['teacher']->hasTeacherRole)
        {
            $addTeacherRole = User::addTeacherRoleToUser($this->data['teacher']->id);
            if($addTeacherRole['error']) {
                \Alert::error($addTeacherRole['message'])->flash();
                abort(404, $addTeacherRole['message']);
            }
            \Alert::success($addTeacherRole['message'])->flash();
        }

        $this->crud->addField([
            'label' => 'Teacher',
            'type'  => 'hidden',
            'name'  => 'teacher_id',
            'value' => request()->teacher_id
        ]);

        $this->crud->addField([
            'label' => 'Level',
            'type'  => 'select_from_array',
            'name'  => 'level_id',
            'options' => YearManagement::whereIn('department_id', Department::active()->pluck('id'))->pluck('year', 'id'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ])->beforeField('track_id');

        $this->crud->addField([
            // Select
            'label' => "Track",
            'type' => 'select_from_array',
            'name' => 'track_id', // the db column for the foreign key
            'options' => [],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ])->beforeField('section_id');

        $this->crud->addField([
            'label' => 'Section',
            'type' => 'select_from_array',
            'name' => 'section_id',
            'options' => [],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Term Type',
            'type' => 'select_from_array',
            'name' => 'term_type',
            'options' => [],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Subject',
            'type' => 'select_from_array',
            'name' => 'subject_id',
            'options' => [],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('section.level', function ($q) use ($column, $searchTerm) {
                    $q->where('year', 'like', '%'.$searchTerm.'%');
                });
            }
        ]);

        // Field For Online Class
        $this->crud->addField([   // color_picker
            'label' => 'Background Color',
            'name' => 'color',
            'type' => 'color_picker',
            'color_picker_options' => [
                'customClass' => 'custom-class'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
            'default' => '#3c8dbc'
        ]);

        // Field For Online Class
        $this->crud->addField([   // color_picker
            'label' => 'Summer',
            'name' => 'summer',
            'type' => 'checkbox',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        // COLUMNS
        $this->crud->addColumn([
            'label' => 'School Year',
            'type' => 'select',
            'name' => 'school_year_id',
            'attribute' => 'schoolYear',
            'entity'    => 'schoolYear',
            'models'    => 'App\Models\SchoolYear'
        ]);

        $this->crud->addColumn([
            'label' => 'Term Type',
            'type' => 'text',
            'name' => 'term_type'
        ]);

        $this->crud->addColumn([
            'label' => 'Level',
            'type' => 'text',
            'name' => 'level_name',
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('section.level', function ($q) use ($column, $searchTerm) {
                    $q->where('year', 'like', '%'.$searchTerm.'%');
                });
            }
        ]);

        $this->crud->addColumn([
            'label' => 'Track',
            'type' => 'text',
            'name' => 'track_name',
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('section.track', function ($q) use ($column, $searchTerm) {
                    $q->where('code', 'like', '%'.$searchTerm.'%');
                });
            }
        ]);


        $this->crud->addColumn([
            'label' => 'Section',
            'type' => 'select',
            'name' => 'section_id',
            'attribute' => 'name',
            'entity'    => 'section',
            'models'    => 'App\Models\SectionManagement',
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('section', function ($q) use ($column, $searchTerm) {
                    $q->where('name', 'like', '%'.$searchTerm.'%');
                });
            }
        ]);

        $this->crud->addColumn([
            'label' => 'Subject',
            'type' => 'text',
            'name' => 'code_subject_name',
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('subject', function ($q) use ($column, $searchTerm) {
                    $q->where('subject_title', 'like', '%'.$searchTerm.'%')
                        ->orWhere('subject_code', 'like', '%'.$searchTerm.'%');
                });
            }
        ]);

        $this->crud->addColumn([
           'name' => 'summer', // The db column name
           'label' => "Summer", // Table column heading
           'type' => 'check'
        ]);


        $this->crud->addColumn([
            'label' => 'Submitted Grades',
            'type' => 'teacherSubject.submittedGrades',
            'name' => 'submitted_grades',
        ]);

        // SCRIPT
        $this->crud->addField([
            'label' => 'script',
            'type' => 'teacherSubject.script',
            'name' => 'teacherSubjectScript',
        ]);
        // END OF SCRIPT

        $this->crud->addClause('with', 'section');
        $this->crud->addClause('where', 'school_year_id', SchoolYear::active()->first()->id);
        
        $this->crud->addClause('where', 'teacher_id', request()->teacher_id);
        // dd($this->crud->model::with('section')->where('school_year_id', SchoolYear::active()->first()->id)->where('teacher_id', request()->teacher_id)->get());
        if(!backpack_auth()->user()->hasRole("School Head")) {
            $this->crud->denyAccess(['create', 'update', 'delete']);
            $this->crud->removeButton('add');
        }

    }

    public function store(StoreRequest $request)
    {
        // Check If The Subject Is Already Taken By (subject_id)
        $term_type = $request->summer ? 'Summer' : $request->term_type;
        if($request->term_type == null) {
            $term_type = 'Full';
        }
        // Set School Year
        $sy = SchoolYear::active()->first();
        if($sy == null) {
            \Alert::warning("No School Year Active")->flash();
            return redirect()->back();   
        }

        $request->request->set('school_year_id', $sy->id);

        // Check if existing row data
        $model = $this->crud->model::where([
            // 'teacher_id' => $request->teacher_id,
            'school_year_id' => $request->school_year_id,
            'section_id'     => $request->section_id,
            'subject_id'     => $request->subject_id,
            'term_type'      => $term_type,
            'summer'         => $request->summer
        ]);

        if($model->exists()) {
            \Alert::warning("This data is already exists or taken")->flash();
            return redirect()->back();
        }
         // Create Online Class
        $subject = SubjectManagement::where('id', $request->subject_id)->first();
        $section = SectionManagement::with('level')->where('id', $request->section_id)->first();
        $teacher = Employee::where('id', $request->teacher_id)->first();

        $class = OnlineClass::where([
            'school_year_id' => $request->school_year_id,
            'section_id'     => $request->section_id,
            'subject_id'     => $request->subject_id,
            'term_type'      => $term_type,
            'summer'         => $request->summer
        ])->first();

        if(!$class) {

            $onlineClass = new OnlineClass();
            $onlineClass->code           = substr(md5(uniqid(mt_rand(), true)) , 0, 7);
            $onlineClass->name           = $subject->subject_code.' '.$section->level->year.' '.$section->name;
            $onlineClass->teacher_id     = $request->teacher_id;
            $onlineClass->subject_id     = $request->subject_id;
            $onlineClass->section_id     = $request->section_id;
            $onlineClass->school_year_id = $request->school_year_id;
            $onlineClass->term_type      = $term_type;
            $onlineClass->summer         = $request->summer;
            $onlineClass->color          = $request->color;
            $onlineClass->save();

        } else {

            $class->name           = $subject->subject_code.' '.$section->level->year.' '.$section->name;
            $class->teacher_id     = $request->teacher_id;
            $class->subject_id     = $request->subject_id;
            $class->section_id     = $request->section_id;
            $class->school_year_id = $request->school_year_id;
            $class->term_type      = $term_type;
            $class->summer         = $request->summer;
            $class->color          = $request->color;
            $class->update();

        }

        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // Check If The Subject Is Already Taken By (subject_id)
        $term_type = $request->summer ? 'Summer' : $request->term_type;
        if($request->term_type == null) {
            $term_type = 'Full';
        }
        // Set School Year
        $sy = SchoolYear::active()->first();
        if($sy == null) {
            \Alert::warning("No School Year Active")->flash();
            return redirect()->back();   
        }

        $request->request->set('school_year_id', $sy->id);

        // Check if existing row data
        $model = $this->crud->model::where('id', '!=', $request->id)->where([
            // 'teacher_id' => $request->teacher_id,
            'school_year_id' => $request->school_year_id,
            'section_id'     => $request->section_id,
            'subject_id'     => $request->subject_id,
            'term_type'      => $term_type,
            'summer'         => $request->summer
        ]);

        if($model->exists()) {
            \Alert::warning("This data is already exists or taken")->flash();
            return redirect()->back();
        }
        // Check If The Online Class Is Already Taken By (subject_id)
        $subject = SubjectManagement::where('id', $request->subject_id)->first();
        $section = SectionManagement::with('level')->where('id', $request->section_id)->first();
        // Check if existing row data
        $class = OnlineClass::where([
            // 'teacher_id' => $request->teacher_id,
            'school_year_id' => $request->school_year_id,
            'section_id'     => $request->section_id,
            'subject_id'     => $request->subject_id,
            'term_type'      => $term_type,
            'summer'         => $request->summer
        ]);

        $subject = SubjectManagement::where('id', $request->subject_id)->first();
        $section = SectionManagement::with('level')->where('id', $request->section_id)->first();
        $teacher = Employee::where('id', $request->teacher_id)->first();

        // Check If Online Class Exist If Exists Update Only
        if(!$class->exists()) {
            // Create Online Class
            $onlineClass = new OnlineClass();
            $onlineClass->code           = substr(md5(uniqid(mt_rand(), true)) , 0, 7);
            $onlineClass->name           = $subject->subject_code.' '.$section->level->year.' '.$section->name;
            $onlineClass->teacher_id     = $request->teacher_id;
            $onlineClass->subject_id     = $request->subject_id;
            $onlineClass->section_id     = $request->section_id;
            $onlineClass->school_year_id = $request->school_year_id;
            $onlineClass->term_type      = $term_type;
            $onlineClass->summer         = $request->summer;
            $onlineClass->color          = $request->color;
            $onlineClass->save();
        }
        else{
            $onlineClass = OnlineClass::where([
                // 'teacher_id'     => $request->teacher_id,
                'school_year_id' => $request->school_year_id,
                'section_id'     => $request->section_id,
                'subject_id'     => $request->subject_id,
                'term_type'      => $request->term_type
            ])->first();

            $onlineClass->name           = $subject->subject_code.' '.$section->level->year.' '.$section->name;
            $onlineClass->teacher_id     = $request->teacher_id;
            $onlineClass->subject_id     = $request->subject_id;
            $onlineClass->section_id     = $request->section_id;
            $onlineClass->school_year_id = $request->school_year_id;
            $onlineClass->term_type      = $term_type;
            $onlineClass->summer         = $request->summer;
            $onlineClass->color          = $request->color;
            $onlineClass->update();
        }

        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function createOnlineClass ($id)
    {
        $teacher_subject = $this->crud->model::where('id', $id)->first();
        if(!$teacher_subject)
        {
            \Alert::warning("Teacher Subject Not Found.")->flash();
            return redirect()->back();
        }
        $subject = SubjectManagement::where('id', $teacher_subject->subject_id)->first();
        $section = SectionManagement::with('level')->where('id', $teacher_subject->section_id)->first();
        $teacher = Employee::where('id', $teacher_subject->teacher_id)->first();
        if(!$subject || !$section || !$teacher)
        {
            \Alert::warning("There's been an error. Online Class might not have been created.")->flash();
            return redirect()->back();
        }
        $onlineClass = new OnlineClass();
        $onlineClass->code           = substr(md5(uniqid(mt_rand(), true)) , 0, 7);
        $onlineClass->name           = $subject->subject_code.' '.$section->level->year.' '.$section->name;
        $onlineClass->teacher_id     = $teacher_subject->teacher_id;
        $onlineClass->subject_id     = $teacher_subject->subject_id;
        $onlineClass->section_id     = $teacher_subject->section_id;
        $onlineClass->school_year_id = $teacher_subject->school_year_id;
        $onlineClass->term_type      = $teacher_subject->summer ? 'Summer' : $teacher_subject->term_type;
        $onlineClass->summer         = $teacher_subject->summer;
        // $onlineClass->color          = $request->color;
        if($onlineClass->save())
        {
            \Alert::success("Successfully Created Online Class.")->flash();
            return redirect()->back();
        }
        else
        {
            \Alert::warning("There's been an error. Online Class might not have been created.")->flash();
            return redirect()->back();
        }

    }

    // Transfer Class To Other Teacher
    public function transferClass(Request $request)
    {
        // Get Teacher Subject
        $teacherSubject =   TeacherSubject::with('subject', 'section', 'section.level', 'teacher')->where('id', $request->teacher_subject_id)->first();
        // Get Employee
        $employee       =   Employee::where('id', $request->employee_id)->first();

        // Validate Teacher Subject Found.
        if(!$teacherSubject)
        {
            \Alert::error("Teacher Subject Not Found!")->flash();
            return redirect()->back();
        }
        // Validate Employee if Found.
        if(!$employee)
        {
            \Alert::error("Employee Not Found!")->flash();
            return redirect()->back();
        }

        // Validate Subject if Found.
        $subject = $teacherSubject->subject;
        if(!$subject)
        {
            \Alert::error("Subject Not Found!")->flash();
            return redirect()->back();
        }

        // Validate Section if Found.
        $section = $teacherSubject->section;
        if(!$section)
        {
            \Alert::error("Section Not Found!")->flash();
            return redirect()->back();
        }

        // Validate Level if Found.
        $level = $teacherSubject->section->level;
        if(!$level)
        {
            \Alert::error("Level Not Found!")->flash();
            return redirect()->back();
        }

        // Get Online Class
        $onlineClass    =   OnlineClass::where('teacher_id', $teacherSubject->teacher_id)
                                        ->where('subject_id', $teacherSubject->subject_id)
                                        ->where('section_id', $teacherSubject->section_id)
                                        ->where('school_year_id', $teacherSubject->school_year_id)
                                        ->where('term_type', $teacherSubject->summer ? 'Summer' : $teacherSubject->term_type)
                                        ->where('summer', $teacherSubject->summer)
                                        ->first();

        // Update Teacher Subject
        $teacherSubject->teacher_id =   $employee->id;
        if($teacherSubject->update())
        {
            // Update Online Class / Create If Online Class Not Found.
            if($onlineClass)
            {
                // Update Online Class
                $onlineClass->teacher_id    =   $teacherSubject->teacher_id;
                if($onlineClass->update())
                {
                    
                }
                else
                {
                    \Alert::warning("There's been an error. Online Class might not have been updated.")->flash();
                    return redirect()->back();
                }
            }
            else
            {
                // Create Online Class
                $onlineClass = new OnlineClass();
                $onlineClass->code           = substr(md5(uniqid(mt_rand(), true)) , 0, 7);
                $onlineClass->name           = $subject->subject_code.' '.$level->year.' '.$section->name;
                $onlineClass->teacher_id     = $teacherSubject->teacher_id;
                $onlineClass->subject_id     = $teacherSubject->subject_id;
                $onlineClass->section_id     = $teacherSubject->section_id;
                $onlineClass->school_year_id = $teacherSubject->school_year_id;
                $onlineClass->term_type      = $teacherSubject->summer ? 'Summer' : $teacherSubject->term_type;
                $onlineClass->summer         = $teacherSubject->summer;
                if($onlineClass->save())
                {

                }
                else
                {
                    \Alert::warning("There's been an error. Online Class might not have been created.")->flash();
                    return redirect()->back();
                }
            }
        }
        else
        {
            \Alert::error('Error Transfering, Something Went Wrong, Please Try Again.')->flash();
            return \Redirect::to($this->crud->route);
        }

        \Alert::success("Class Successfully Transfered!")->flash();
        return redirect()->back();
    }

    public function getTracks ()
    {
        $tracks = TrackManagement::where('level_id', request()->level_id)->active()->get();
        return $tracks;
    }

    public function getSections ()
    {
        $sections = SectionManagement::where([ 
                                                'level_id' => request()->level_id, 
                                                'track_id' => request()->track_id 
                                        ])->get();
        return $sections ?? [];
    }

    public function getSubjects ()
    {
        $section = SectionManagement::where('id', request()->section_id)->first();

        if($section == null) { return []; }

        $term_type = isset(request()->term_type) ? request()->term_type : 'Full';

        $subject_mapping  = SubjectMapping::where([ 
                                'level_id'      => $section->level_id,  
                                'curriculum_id' => $section->curriculum_id,
                                'track_id'      => $section->track_id,
                                'term_type'     => $term_type,
                            ])->first();

        if(!$subject_mapping) { return []; }

        $subject_ids    = collect($subject_mapping->subjects)->pluck('subject_code')->toArray();
        $subject_taken  = TeacherSubject::where('school_year_id', SchoolYear::active()->first()->id)->where('section_id', request()->section_id)->get()->pluck('subject_id');

        $subjects  = SubjectManagement::whereIn('id', $subject_ids)->with('childrens')->get();
        $subjectCollections = collect($subjects);
        
        // Get Subject That Has No Child
        $parent = $subjectCollections->map(function ($item, $key) {
            if(count($item->childrens) < 1) {
                return $item;
            }
        })->filter()->toArray();

        // Get Subject That Has Childs
        $childs = $subjectCollections->map(function ($item, $key) {
            if(count($item->childrens) > 0) {
                return $item->childrens;
            }
        })->filter();

        foreach ($childs as $child) {
            foreach($child as $c) { $parent[] = $c; }
        }

        $subjects = $parent;
        return response()->json($subjects);
    }

    public function getTerms ()
    {
        $section = SectionManagement::where('id', request()->section_id)->first();

        if($section) {
            $yearLevel = YearManagement::where('id', $section->level_id)->first();
            if($yearLevel) {
                $term = TermManagement::where('department_id', $yearLevel->department_id)->first();
                if($term) {
                    if($term->type == "Semester") {
                        return ["First", "Second"];
                    }
                }
            }
        }

        return [];
    }
}
