<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\OnlineClassTopicRequest as StoreRequest;
use App\Http\Requests\OnlineClassTopicRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

// MODELS
use App\Models\OnlineTopicPage;
use App\Models\OnlineClassTopic;
use App\Models\OnlineClassModule;
use App\Models\OnlineClass;
use App\Models\OnlineCourse;
use App\Models\YearManagement;
use App\Models\SchoolYear;
use App\Models\Quiz;

use App\Models\OnlineClassAttendance;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class OnlineClassTopicCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class OnlineClassTopicCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\OnlineClassTopic');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/online-class-topic');
        $this->crud->setEntityNameStrings('Class Topic', 'Class Topics');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        // add asterisk for fields that are required in OnlineClassTopicRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        // dd( \Route::current()->parameter('edit'));
        /*
        |--------------------------------------------------------------------------
        | Get The Teacher Information
        |--------------------------------------------------------------------------
        */
        if(!request()->has('course_code') || !request()->has('module_id')) {
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

        // Check If The Course Is Created By the Teacher Or Tag
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
        
        $this->data['user']     =   backpack_user();
        $this->data['topics']   =   $this->crud->model::with('module', 'module.course')
                                        ->where('online_class_module_id', $this->data['module']->id)
                                        ->where('online_course_id', $course->id)
                                        ->get();

        /*
        |--------------------------------------------------------------------------
        | FIELDS
        |--------------------------------------------------------------------------
        */
         $this->crud->addField([   // Textarea
            'name' => 'online_class_module_id',
            'label' => '',
            'type' => 'hidden',
            'value' => $this->data['module']->id
        ]);
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
               'id' => 'content'
            ],
            'extra_plugins' => ['oembed', 'widget', 'justify', 'grid', 'mathjax', 'font', 'autocomplete', 'slideshow'],
        ]);
        //  $this->crud->addField([
        //     'name' => 'filessq',
        //     'label' => '',
        //     'type' => 'onlineClass.topic.files',
        //     'upload' => true,
        //     'disk' => 'uploads'
        // ]);
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

        if(!request()->has('topic_id')){
           $this->crud->setListView('onlineClass.classTopics.dashboard'); 
        }
        else{
            $this->showTopic(request()->topic_id);
        }
        $online_class_topic_id = \Route::current()->parameter('online_class_topic');
        if($online_class_topic_id)
        {
           if($online_class_topic_id != request()->topic_id)
           {
                abort(403, 'Mismatch topic id');
           }
        }
        $this->data['imageExtensions'] = ['jpg'=>'jpg', 'jpeg'=>'jpeg', 'gif'=>'gif', 'png'=>'png', 'bmp'=>'bmp', 'svg'=>'svg', 'svgz'=>'svgz', 'cgm'=>'cgm', 'djv'=>'djv', 'djvu'=>'djvu', 'ico'=>'ico', 'ief'=>'ief','jpe'=>'jpe', 'pbm'=>'pbm', 'pgm'=>'pgm', 'pnm'=>'pnm', 'ppm'=>'ppm', 'ras'=>'ras', 'rgb'=>'rgb', 'tif'=>'tif', 'tiff'=>'tiff', 'wbmp'=>'wbmp', 'xbm'=>'xbm', 'xpm'=>'xpm', 'xwd'=>'xwd'];

        if(backpack_user()->hasRole('School Head') || backpack_auth()->user()->employee_id == $course->teacher_id)
        {
            $this->crud->setCreateView('onlineClass.classTopics.create');
            $this->crud->setEditView('onlineClass.classTopics.edit');
        }
        else
        {
            if(request()->topic_id){
                $this->crud->setCreateView('onlineClass.classTopics.show');
                $this->crud->setEditView('onlineClass.classTopics.show');
            }
            else{
                $this->crud->setCreateView('onlineClass.classTopics.dashboard');
                $this->crud->setEditView('onlineClass.classTopics.dashboard');
            }
        }

        if(request()->topic_page)
        {
            $this->showTopicPage(request()->topic_page);
        }
    }

    // Show Topic
    public function showTopic($topic_id)
    {
        $this->data['selected_topic']   =   $this->crud->model::with('module', 'module.course', 'module.topics')
                                                    ->where('online_class_module_id', request()->module_id)
                                                    ->where('online_course_id', $this->data['course']->id)
                                                    ->where('id', $topic_id)
                                                    ->first();
        $this->data['selected_topic_pages'] =   OnlineTopicPage::where('online_class_topic_id', $topic_id)
                                                ->paginate(1);
        if(!$this->data['selected_topic']) {
            abort(404, 'Topic not Found');
        }
        $this->data['next_topic']   =   $this->crud->model::with('module', 'module.course', 'module.topics')
                                                ->where('online_class_module_id', request()->module_id)
                                                ->where('online_course_id', $this->data['course']->id)
                                                ->where('id', '>', $topic_id)
                                                ->first();
        $this->data['prev_topic']   =   $this->crud->model::with('module', 'module.course', 'module.topics')
                                                ->where('online_class_module_id', request()->module_id)
                                                ->where('online_course_id', $this->data['course']->id)
                                                ->where('id', '<', $topic_id)
                                                ->first();

        // $this->data['quizzes']      =   Quiz::where('user_id', backpack_auth()->user()->id)->get();
        $this->crud->setListView('onlineClass.classTopics.show');

    }

    public function getQuiz()
    {
        $quizzes = Quiz::where('user_id', backpack_auth()->user()->id)->get();
        return $quizzes;
    }

    // Fetching Of Topic Page Ajax
    public function ajaxFetchPage(Request $request)
    {
        $topic_id = $request->topic_id;
        if($request->ajax())
        {
            $course         =   $this->data['course'];
            $selected_topic =   $this->crud->model::with('module', 'module.course', 'module.topics')
                                                    ->where('online_class_module_id', request()->module_id)
                                                    ->where('online_course_id', $this->data['course']->id)
                                                    ->where('id', $topic_id)
                                                    ->first();
            $selected_topic_pages =   OnlineTopicPage::where('online_class_topic_id', $topic_id)
                                                ->paginate(1);
            $view = view('onlineClass.classTopics.ajaxPage',compact('course', 'selected_topic', 'selected_topic_pages'))->render();
            return response()->json(['html'=>$view]); 
         }

    }

    // Show Topic's Page
    public function showTopicPage($page_id)
    {
        $this->data['selected_page']    =   OnlineTopicPage::where('id', $page_id)
                                                ->where('online_class_topic_id', $this->data['selected_topic']->id)
                                                ->first();
        if(!$this->data['selected_page'])
        {
            abort(404, 'Topic Page Not Found');
        }
        $this->data['next_topic_page']  =   OnlineTopicPage::where('id', '>', $this->data['selected_page']->id)
                                                ->where('online_class_topic_id',$this->data['selected_topic']->id)
                                                ->first();

        $this->data['prev_topic_page']  =   OnlineTopicPage::where('id', '<', $this->data['selected_page']->id)
                                                ->where('online_class_topic_id', $this->data['selected_topic']->id)
                                                ->first();

        $this->crud->setListView('onlineClass.classTopics.show_page');    
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
        // If Has File Delete Existing and Change To New
        if($request->hasFile('files')){
            $model = $this->crud->model::where('id', $request->id)->first();
            if($model->files)
            {
                if(count($model->files)>0)
                {
                    foreach ($model->files as $key => $file) {
                        if($file['filepath'])
                        {
                            if(\File::exists($file['filepath'])) {
                               \File::delete($file['filepath']);
                            }
                        }
                    }
                }
            }
            $this->crud->model::where('id', $request->id)
                    ->update(['files' => null]);

        }
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        // return $redirect_location;
       return redirect('admin/online-class-topic?course_code='.request()->course_code.'&module_id='.request()->module_id.'&topic_id='.request()->topic_id);
    }

    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');

        $this->crud->delete($id);

        return redirect('admin/online-class-topic?course_code='.request()->course_code.'&module_id='.request()->module_id);
    }
}
