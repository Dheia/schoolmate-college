<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\OnlineClassModuleRequest as StoreRequest;
use App\Http\Requests\OnlineClassModuleRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

// MODELS
use App\Models\OnlineClassTopic;
use App\Models\OnlineClassModule;
use App\Models\OnlineCourse;
use App\Models\OnlineClass;
use App\Models\YearManagement;
use App\Models\SchoolYear;
use App\Models\User;
use App\Models\Employee;

use App\Models\OnlineClassAttendance;

/**
 * Class OnlineClassModuleCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class OnlineClassModuleCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\OnlineClassModule');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/online-class-module');
        $this->crud->setEntityNameStrings('Course Module', 'Course Modules');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        // add asterisk for fields that are required in OnlineClassModuleRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->setListView('onlineClass.classModules.dashboard');

        /*
        |--------------------------------------------------------------------------
        | Get The Teacher Information
        |--------------------------------------------------------------------------
        */
        if(!request()->course_code ) {
            abort(403, 'Missing Required Parameters');
        } 
        else {
            
            if(backpack_auth()->user()->employee_id === null) {
                abort(403, 'Your User Account Is Not Yet Tag As Employee');
            }
            $this->data['teacher'] = User::where('employee_id', backpack_auth()->user()->employee_id)->first();

            if($this->data['teacher'] === null) {
                abort(403, 'User Not Found');
            }
        }
        $course_tag     =   OnlineCourse::with('teacher', 'share')
                                ->join('online_course_teacher', function ($join) {
                                    $join->on('online_course_teacher.online_course_id', '=', 'online_courses.id')
                                         ->where('online_course_teacher.teacher_id', '=',  backpack_auth()->user()->employee_id);
                                })
                                ->notArchive()
                                ->active()
                                ->get();
        $this->data['course']    =   $course  =   OnlineCourse::with('subject', 'level', 'teacher', 'modules')
                                                            ->where('code', request()->course_code)
                                                            ->active()
                                                            ->notArchive()
                                                            ->first();
        if(!$course) {
            abort(403, 'Mismatch Course');
        }
        if(backpack_user()->hasRole('School Head')){

            $this->data['my_courses']   =   OnlineCourse::with('subject', 'level', 'teacher', 'modules')
                                                ->notArchive()
                                                ->active()
                                                ->limit(10)
                                                ->get();
        }
        else if(backpack_user()->hasRole('Teacher')){
            $this->data['my_courses']   =   OnlineCourse::where('teacher_id', backpack_auth()->user()->employee_id)
                                                ->with('subject', 'level', 'teacher', 'modules')
                                                ->orWhereIn('id', $course_tag->pluck('online_course_id'))
                                                ->notArchive()
                                                ->active()
                                                ->limit(10)
                                                ->get();

        }
        if(!backpack_user()->hasRole('School Head')){
            if($course->teacher_id != backpack_auth()->user()->employee_id){
                if(count($course_tag) == 0)
                {
                    abort(403, 'Mismatch User and Course');
                }
                else if(!$course_tag->where('online_course_id', $course->id)->first()){
                    abort(403, 'Mismatch User and Course');
                }
            }
        }
        $this->data['user'] = backpack_user();
        $this->data['modules']   =   $this->crud->model::with('course','course.modules')->where('online_course_id', $course->id)->get();

        // Allow Create/Edit If School Head Or Course Teacher
        if(backpack_user()->hasRole('School Head') || backpack_auth()->user()->employee_id == $course->teacher_id)
        {
            $this->crud->setCreateView('onlineClass.classModules.create');
            $this->crud->setEditView('onlineClass.classModules.edit');
        }

        /*
        |--------------------------------------------------------------------------
        | If Parameter Has Class Code 
            Validate  If Class Course ID Is Equal To Course ID Of The Module
        |--------------------------------------------------------------------------
        */
        if(request()->class_code){
            $class = OnlineClass::where('code', request()->class_code)->first();
            if(!$class){
                abort(403, 'Mismatch Class');
            }
            elseif($class->online_course_id != $course->id){
                abort(403, 'Mismatch Class and Course');
            }
            $this->data['class_attendance'] =   OnlineClassAttendance::getEmployeeAttendanceToday($class->id, backpack_user()->employee_id);
        }
        else{
            if(backpack_auth()->user()->employee_id != $course->teacher_id){
                if(!backpack_user()->hasRole('School Head'))
                {
                    if(count($course_tag) == 0)
                    {
                        abort(403, 'Mismatch User and Course');
                    }
                    else if(!$course_tag->where('online_course_id', $course->id)->first()){
                        abort(403, 'Mismatch User and Course');
                    }
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | FIELDS
        |--------------------------------------------------------------------------
        */
        $this->crud->addField([   // Textarea
            'name' => 'title',
            'label' => 'Title',
            'type' => 'text',
            'attributes' => [
               'placeholder' => 'Title'
            ],
        ]);
        $this->crud->addField([   // Textarea
            'name' => 'description',
            'label' => 'Description',
            'type' => 'ckeditor',
            'attributes' => [
               'placeholder' => 'Description'
            ],
            'extra_plugins' => ['oembed', 'widget', 'justify', 'grid', 'mathjax', 'font', 'autocomplete', 'tableresize'],
        ]);
        $this->crud->addField([   // Textarea
            'name' => 'content_standard',
            'label' => 'Content Standard',
            'type' => 'ckeditor',
            'attributes' => [
               'placeholder' => 'Content Standard'
            ],
            'extra_plugins' => ['oembed', 'widget', 'justify', 'grid', 'mathjax', 'font', 'autocomplete', 'tableresize'],
        ]);
        $this->crud->addField([   // Textarea
            'name' => 'performance_standard',
            'label' => 'Peroformance Standard',
            'type' => 'ckeditor',
            'attributes' => [
               'placeholder' => 'Performance Standard'
            ],
            'extra_plugins' => ['oembed', 'widget', 'justify', 'grid', 'mathjax', 'font', 'autocomplete', 'tableresize'],
        ]);
        $this->crud->addField([   // Textarea
            'name' => 'learning_competency',
            'label' => 'Learning Competency',
            'type' => 'ckeditor',
            'attributes' => [
               'placeholder' => 'Learning Competency'
            ],
            'extra_plugins' => ['oembed', 'widget', 'justify', 'grid', 'mathjax', 'font', 'autocomplete', 'tableresize'],
        ]);
         $this->crud->addField([   // Textarea
            'name' => 'learning_objective',
            'label' => 'Learning Objective',
            'type' => 'ckeditor',
            'attributes' => [
               'placeholder' => 'Learning Objective'
            ],
            'extra_plugins' => ['oembed', 'widget', 'justify', 'grid', 'mathjax', 'font', 'autocomplete', 'tableresize'],
        ]);
        $this->crud->addField([
            'label' => 'online_course_id',
            'type'  => 'hidden',
            'name'  => 'online_course_id',
            'value' => $this->data['course']->id
        ]);
        $this->crud->addField([
            'label' => 'teacher_id',
            'type'  => 'hidden',
            'name'  => 'teacher_id',
            'value' => $this->data['course']->teacher_id
        ]);

        $online_class_module_id = \Route::current()->parameter('online_class_module');
        if($online_class_module_id)
        {
           if($online_class_module_id != request()->module_id)
           {
                abort(403, 'Mismatch module id');
           }
           $this->data['module'] = $this->crud->entry;
        }

    }

    public function store(StoreRequest $request)
    {        
        $this->data['course']    =   $course  =   OnlineCourse::with('subject', 'level', 'teacher', 'modules')
                                                            ->where('code', request()->course_code)
                                                            ->active()
                                                            ->notArchive()
                                                            ->first();
        if(!$course) {
            abort(403, 'Mismatch Course');
        }

        if(!backpack_user()->hasRole('School Head')){
            if($course->teacher_id != backpack_auth()->user()->employee_id){
                if(count($course_tag) == 0)
                {
                    abort(403, 'Mismatch User and Course');
                }
                else if(!$course_tag->where('online_course_id', $course->id)->first()){
                    abort(403, 'Mismatch User and Course');
                }
            }
        }

        $request->request->set("online_course_id", $course->id);
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        // return $redirect_location;
        return redirect('admin/online-course?course_code='.request()->course_code);
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');

        $module = $this->crud->model::with('course')->where('id', $id)->first();
        if(!$module)
        {
            abort('404', 'Module Not Found.');
        }
        if(!backpack_user()->hasRole('School Head')){
            if($module->course->teacher_id != backpack_auth()->user()->employee_id){
                abort(403);
            }
        }

        $this->crud->delete($id);

        return redirect('admin/online-class-module?course_code='.request()->course_code);
    }
}
