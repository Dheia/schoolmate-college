<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\OnlineTopicPageRequest as StoreRequest;
use App\Http\Requests\OnlineTopicPageRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Redirect;


// MODELS
use App\Models\OnlineClassTopic;
use App\Models\OnlineClassModule;
use App\Models\OnlineClass;
use App\Models\OnlineCourse;
use App\Models\YearManagement;
use App\Models\SchoolYear;

use App\Models\OnlineClassAttendance;

/**
 * Class OnlineTopicPageCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class OnlineTopicPageCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\OnlineTopicPage');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/online-class-topic-page');
        $this->crud->setEntityNameStrings('Topic Page', 'Topic Pages');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        // add asterisk for fields that are required in OnlineTopicPageRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

         /*
        |--------------------------------------------------------------------------
        | Get The Teacher Information
        |--------------------------------------------------------------------------
        */
        if(!request()->has('course_code') || !request()->has('module_id') || !request()->has('topic_id')) {
            \Alert::warning("Missing Required Parameters!")->flash();
            abort(403);
        } else {
                
            if(backpack_auth()->user()->employee_id === null) {
                abort(403, 'Your User Account Is Not Yet Tag As Employee');
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
        $course  =   OnlineCourse::with('subject', 'level', 'teacher', 'modules')
                                        ->where('code', request()->course_code)
                                        ->notArchive()
                                        ->active()
                                        ->first();
        if(!$course) {
            abort(403, 'Mismatch Class');
        }
        $this->data['course']   =   $course;
        $this->data['module']   =   OnlineClassModule::with('course', 'course.modules')
                                        ->where('id', request()->module_id)
                                        ->first();

        if(!$this->data['module']){
            abort(403, 'Mismatch Module');
        }
        else if($this->data['module']->online_course_id != $course->id){
            abort(403, 'Mismatch Module');
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

        $this->data['selected_topic']   =   OnlineClassTopic::with('module', 'module.course', 'module.topics')
                                                ->where('online_class_module_id', request()->module_id)
                                                ->where('online_course_id', $course->id)
                                                ->where('id', request()->topic_id)
                                                ->first();

        if(!$this->data['selected_topic']) {
            abort(404, 'Topic not Found');
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
                if(!$course->whereNotIn('id', $course_tag->pluck('online_course_id'))){
                    abort(403, 'Mismatch User and Course');
                }
            }
        }
        
        $this->data['user']     =   backpack_user();
        $this->data['topics']   =   OnlineClassTopic::with('module', 'module.course')
                                        ->where('online_class_module_id', $this->data['module']->id)
                                        ->where('online_course_id', $course->id)
                                        ->get();

        $this->crud->setCreateView('onlineClass.classTopicPages.create');
        $this->crud->setEditView('onlineClass.classTopicPages.edit');


        $this->crud->addField([   // Textarea
            'name' => 'title',
            'label' => '',
            'type' => 'text',
            'attributes' => [
               'placeholder' => 'Title'
            ],
        ]);

        $this->crud->addField([   // Textarea
            'name' => 'description',
            'label' => '',
            'type' => 'ckeditor',
            'attributes' => [
               'placeholder' => 'Description',
               'id' => 'content',
               'contenteditable'=>"true"
            ],
             // optional:
            'extra_plugins' => ['oembed', 'widget', 'justify', 'grid', 'mathjax', 'font', 'autocomplete', 'tableresize', 'slideshow'],
            // 'options' => [
            //     'autoGrow_minHeight' => 200,
            //     'autoGrow_bottomSpace' => 50,
            //     'removePlugins' => '',
               
            // ]
        ]);

        $this->crud->addField([
            'label' => 'Custom Script',
            'type' => 'onlineClass/topicPage/custom_script',
            'name' => 'custom_script'
        ]);
    }

    public function create($page_type = false)
    {
        // if($this->data['selected_topic'])
        $page_type  =   request()->page_type;
        if(!$page_type)
        {
            // Add some alerts and flash them to the session.
            \Alert::warning('Page Type Not Found')->flash();
            abort(404);
        }
        if(!backpack_user()->hasRole('School Head'))
        {
            if($this->data['selected_topic']->module->course->teacher_id != backpack_auth()->user()->employee_id)
            {
                abort(401, 'Unauthorized action.');
            }
        }
        /*
        |--------------------------------------------------------------------------
        | FIELDS
        |--------------------------------------------------------------------------
        */
        $this->crud->addField([   // Textarea
            'name' => 'online_class_topic_id',
            'label' => '',
            'type' => 'hidden',
            'value' => request()->topic_id
        ]);

        $this->crud->addField([   // Textarea
            'name' => 'type',
            'label' => '',
            'type' => 'hidden',
            'value' => $page_type,

        ]);

        if($page_type == 'discussion')
        {
            $this->crud->addField([
                'name' => 'files2',
                'label' => '',
                'type' => 'onlineClass.topic.files',
                'upload' => true,
                'disk' => 'uploads'
            ]);
        }
        else if($page_type == 'video')
        {
            $this->crud->addField([
                'name' => 'video',
                'label' => 'Video',
                'type' => 'teacherOnlineClass.video',
            ]);

            $this->crud->addField([
                'name' => 'files',
                'label' => 'Files',
                'type' => 'upload_multiple',
                'upload' => true,
                'disk' => 'uploads'
            ]);

        }
        else
        {
            \Alert::warning('Invalid Page Type')->flash();
            abort(401);
        }

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
        return parent::create($page_type);
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        // return $redirect_location;
        return redirect('admin/online-class-topic?course_code='.request()->course_code.'&module_id='.request()->module_id.'&topic_id='.request()->topic_id);
    }

    public function edit($page_type = false)
    {
        $page_id = \Route::current()->parameter('online_class_topic_page');
        $page = $this->crud->model::where('online_class_topic_id', request()->topic_id)->where('id', $page_id)->first();
        if(!$page)
        {
            \Alert::warning("Topic`s Page Not Found")->flash();
                abort(404);
        }
        if(!backpack_user()->hasRole('School Head'))
        {
            if($this->data['selected_topic']->module->course->teacher_id != backpack_auth()->user()->employee_id)
            {
                \Alert::warning('Unauthorized action.')->flash();
                abort(401);
            }
        }

        if($page->type == 'discussion')
        {
            $this->crud->addField([
                'name' => 'file_upload',
                'label' => '',
                'type' => 'onlineClass.topic.files',
                'upload' => true,
                'disk' => 'uploads'
            ]);
        }
        else if($page->type == 'video')
        {
            $this->crud->addField([
                'name' => 'video',
                'label' => 'Video',
                'type' => 'teacherOnlineClass.video',
            ]);

            $this->crud->addField([
                'name' => 'file_upload',
                'label' => '',
                'type' => 'onlineClass.topic.files'
            ]);

        }
        else
        {
            return redirect('admin/online-class-topic?course_code='.request()->course_code.'&module_id='.request()->module_id.'&topic_id='.request()->topic_id);
        }

        return parent::edit($page_type);
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        // return $redirect_location;
        return redirect('admin/online-class-topic?course_code='.request()->course_code.'&module_id='.request()->module_id.'&topic_id='.request()->topic_id);
    }

    public function delete($id)
    {
        $page_id = \Route::current()->parameter('online_class_topic_page');
        $page = $this->crud->model::where('online_class_topic_id', request()->topic_id)->where('id', $id)->where('id', request()->page)->first();
        if(!$page)
        {
            \Alert::warning("Topic`s Page Not Found")->flash();
                abort(404);
        }
        if(!backpack_user()->hasRole('School Head'))
        {
            if($this->data['selected_topic']->module->course->teacher_id != backpack_auth()->user()->employee_id)
            {
                \Alert::warning('Unauthorized action.')->flash();
                abort(401);
            }
        }

        $this->crud->delete($id);

        return redirect('admin/online-class-topic?course_code='.request()->course_code.'&module_id='.request()->module_id.'&topic_id='.request()->topic_id);
    }
}
