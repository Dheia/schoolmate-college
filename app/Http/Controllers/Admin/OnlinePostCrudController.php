<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\OnlinePostRequest as StoreRequest;
use App\Http\Requests\OnlinePostRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class OnlinePostCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */

use Symfony\Component\HttpFoundation\Request;

// Jobs
use App\Jobs\PublishOnlineClassPostJob;

// Events
use App\Events\PostCrudEvent;
use App\Events\CommentCrudEvent;

// MODELS
use App\Models\Quiz;
use App\Models\OnlinePost;
use App\Models\OnlineClass;
use App\Models\OnlineComment;
use App\Models\OnlineClassQuiz;
use App\Models\OnlineClassAttendance;

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

use App\Http\Controllers\BBB;

use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\GetRecordingsParameters;

use DB;

class OnlinePostCrudController extends CrudController
{
    public $class    = null;
    public function setup()
    {
        
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\OnlinePost');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/online-post');
        $this->crud->setEntityNameStrings('Online Post', 'Online Post');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        // add asterisk for fields that are required in OnlinePostRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        // $this->crud->setListView('teacherOnlinePost.list');
        // $this->crud->setCreateView('teacherOnlinePost.create');
        // $this->crud->setEditView('teacherOnlinePost.edit');
        $this->crud->setListView('teacherOnlinePost.home');
        $this->crud->setCreateView('teacherOnlinePost.home');
        $this->crud->setEditView('teacherOnlinePost.home');


        $this->crud->removeColumns(['teacher_id']);
        $this->crud->removeFields(['teacher_id']);

        /*
        |--------------------------------------------------------------------------
        | Get The Teacher Information
        |--------------------------------------------------------------------------
        */
        if(backpack_auth()->user()->employee === null) {
            abort(403, 'Your User Account Is Not Yet Tag As Employee');
        }

        /*
        |--------------------------------------------------------------------------
        | Get Classes and Video Conference Status
        |--------------------------------------------------------------------------
        */
        $this->data['my_classes']               =   $this->getMyClasses();
        // $this->data['video_conference_status']  =   $this->getVideoConferenceStatus();

        // dd($this->data['video_conference_status']);
        
        /*
        |--------------------------------------------------------------------------
        | Check If URL Parameter Didn't Have Teacher ID And Class Code
        |--------------------------------------------------------------------------
        */
        if(!request()->has('class_code')) {
            // Get Classes Posts
            $this->data['posts']            =   OnlinePost::with('comments', 'comments.commentable', 'subject', 'section', 'class', 'classQuiz', 'classQuiz.quiz')
                                                    ->whereIn('online_class_id', collect($this->data['my_classes'])->pluck('id'))
                                                    ->orderBy('created_at', 'DESC')
                                                    ->notArchive()
                                                    ->active()
                                                    ->get();
        } 
        else {
            // Get Class Posts
            $this->crud->setListView('teacherOnlinePost.class-posts');
            $this->crud->setCreateView('teacherOnlinePost.class-posts');
            $this->crud->setEditView('teacherOnlinePost.class-posts');
            $this->class = $this->getClass(request()->class_code);
            if(!$this->class){
                abort(403, 'Mismatch Class');
            }
            $this->data['class']    =   $this->class;

            $this->data['posts']    =   OnlinePost::with('comments', 'comments.commentable', 'classQuiz', 'classQuiz.quiz')
                                                    ->orderBy('created_at', 'DESC')
                                                    ->where('online_class_id', $this->data['class']->id)
                                                    ->active()
                                                    ->notArchive()
                                                    ->get();

            $this->data['class_attendance'] =   OnlineClassAttendance::getEmployeeAttendanceToday($this->class->id, backpack_user()->employee_id);

            $this->crud->addField([
                'label' => 'online_class_id',
                'type'  => 'hidden',
                'name'  => 'online_class_id',
                'value' => $this->data['class']->id
            ]);
            $this->crud->addField([
                'label' => 'post_type',
                'type'  => 'hidden',
                'name'  => 'post_type',
                'value' => 'Single'
            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | FIELDS
        |--------------------------------------------------------------------------
        */
        $this->crud->addField([   // Textarea
            'name' => 'content',
            'label' => '',
            'type' => 'textarea',
            'attributes' => [
               'placeholder' => 'Type your note here...',
               'id' => 'content'
            ],
        ]);

        if(!request()->class_code){
            $this->crud->addField([
                'label' => 'post_type',
                'type'  => 'hidden',
                'name'  => 'post_type',
                'value' => 'Group'
            ]);
            if(backpack_user()->hasRole('School Head')){
                $this->crud->addField([       // Select2Multiple = n-n relationship (with pivot table)
                    'label' => "Select Group",
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
                ]);
            }
            else{
                $this->crud->addField([       // Select2Multiple = n-n relationship (with pivot table)
                    'label' => "Select Group",
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
                ]);
            }
        }

        // onlineClass.files has a field with a name "files" use to store in Database
         $this->crud->addField([
            'name' => 'select_files',
            'label' => '',
            'type' => 'onlineClass.files',
            'upload' => true,
            'disk' => 'uploads'
        ]);

        // QUIZ FIELDS
        $this->crud->addField([   // Checkbox
            'name' => 'cb_addQuiz',
            'label' => 'Add Quiz',
            'type' => 'checkbox',
            'attributes' => [
               'id' => 'cb_addQuiz'
            ],
        ]);

        $this->crud->addField([ // select_from_array
            'name' => 'quiz_id',
            'label' => "Select Quiz",
            'type' => 'select2_from_array',
            'options' => Quiz::where('teacher_id',backpack_auth()->user()->employee_id)
                            ->get()->pluck('title', 'id'),
            'allows_null' => false,
            // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12 quiz-field'
            ],
        ]);

        $this->crud->addField([ // select_from_array
            'name' => 'start_at',
            'label' => "Start At",
            'type' => 'datetime',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6 quiz-field'
            ],
            'default' => date("Y-m-d H:i"),
        ]);

        $this->crud->addField([ // select_from_array
            'name' => 'end_at',
            'label' => "End At",
            'type' => 'datetime',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6 quiz-field'
            ],
            'default' => date("Y-m-d H:i"),
        ]);

        $this->crud->addField([   // Checkbox
            'name' => 'allow_late_submission',
            'label' => 'Allow Late Submission',
            'type' => 'checkbox',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4 quiz-field'
            ],
        ]);

        $this->crud->addField([   // Checkbox
            'name' => 'shuffle',
            'label' => 'Shuffle Question',
            'type' => 'checkbox',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4 quiz-field'
            ],
        ]);

        $this->crud->addField([   // Checkbox
            'name' => 'allow_retake',
            'label' => 'Allow Retake',
            'type' => 'checkbox',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4 quiz-field'
            ],
        ]);

        $this->crud->addField([
            'name' => 'online_post_script',
            'label' => '',
            'type' => 'onlineClass.online_post_script'
        ]);

         $this->data['imageExtensions'] = ['jpg'=>'jpg', 'jpeg'=>'jpeg', 'gif'=>'gif', 'png'=>'png', 'bmp'=>'bmp', 'svg'=>'svg', 'svgz'=>'svgz', 'cgm'=>'cgm', 'djv'=>'djv', 'djvu'=>'djvu', 'ico'=>'ico', 'ief'=>'ief','jpe'=>'jpe', 'pbm'=>'pbm', 'pgm'=>'pgm', 'pnm'=>'pnm', 'ppm'=>'ppm', 'ras'=>'ras', 'rgb'=>'rgb', 'tif'=>'tif', 'tiff'=>'tiff', 'wbmp'=>'wbmp', 'xbm'=>'xbm', 'xpm'=>'xpm', 'xwd'=>'xwd'];

        $this->data['user'] = backpack_user();

        // Check If User is Substitute Teacher Of Class and Video Status
        if($this->class){
            $this->class->isTeacherSubstitute   = 0;
           
            if($this->class->substitute_teachers){
                if(count($this->class->substitute_teachers)>0){
                    if(in_array(backpack_auth()->user()->employee_id, $this->class->substitute_teachers)){
                        $this->class->isTeacherSubstitute = 1;
                    }
                }
            }
        }
    }

    public function store(StoreRequest $request)
    {
        
        if($request->post_type == 'Group'){
            $classes = OnlineClass::whereIn('id', $request->online_class_id)->get();
            foreach ($classes as $key => $class) {
                // $class = OnlineClass::where('id', $class->id)->first();
                $post = new OnlinePost();
                $post->online_class_id  =   $class->id;
                $post->content          =   nl2br($request->content);
                $post->files            =   $request->files;
                $post->teacher_id       =   backpack_auth()->user()->employee_id;
                $post->subject_id       =   $class->subject_id;
                $post->section_id       =   $class->section_id;
                $post->school_year_id   =   $class->school_year_id;
                $post->term_type        =   $class->term_type;
                $post->save();

                if($request->cb_addQuiz)
                {
                    $onlineClassQuiz    =   OnlineClassQuiz::create([
                                                'online_class_id'           => $post->online_class_id,
                                                'quiz_id'                   => $request->quiz_id,
                                                'online_post_id'            => $post->id,
                                                'start_at'                  => $request->start_at,
                                                'end_at'                    => $request->end_at,
                                                'allow_late_submission'     => $request->allow_late_submission,
                                                'allow_retake'              => $request->allow_retake,
                                                'shuffle'                   => $request->shuffle,
                                                'school_year_id'            => (int)$class->school_year_id
                                            ]);
                }

                $post = OnlinePost::with('comments', 'comments.commentable')->where('id', $post->id)->first();
                event(new PostCrudEvent($post, 'Store'));

                PublishOnlineClassPostJob::dispatch($post);
            }
        }
        else if($request->post_type == 'Single'){
            $class = OnlineClass::where('id', $request->online_class_id)->first();
            $post = new OnlinePost();
                $post->online_class_id  =   $class->id;
                $post->content          =   nl2br($request->content);
                $post->files            =   $request->files;
                $post->teacher_id       =   backpack_auth()->user()->employee_id;
                $post->subject_id       =   $class->subject_id;
                $post->section_id       =   $class->section_id;
                $post->school_year_id   =   $class->school_year_id;
                $post->term_type        =   $class->term_type;
                $post->save();

                if($request->cb_addQuiz)
                {
                    $onlineClassQuiz    =   OnlineClassQuiz::create([
                                                'online_class_id'           => $post->online_class_id,
                                                'quiz_id'                   => $request->quiz_id,
                                                'online_post_id'            => $post->id,
                                                'start_at'                  => $request->start_at,
                                                'end_at'                    => $request->end_at,
                                                'allow_late_submission'     => $request->allow_late_submission,
                                                'allow_retake'              => $request->allow_retake,
                                                'shuffle'                   => $request->shuffle,
                                                'school_year_id'            => (int)$class->school_year_id
                                            ]);
                }

                $post = OnlinePost::with('comments', 'comments.commentable')->where('id', $post->id)->first();
                event(new PostCrudEvent($post, 'Store'));

                PublishOnlineClassPostJob::dispatch($post);
        }

        // your additional operations before save here
        // $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        // return $redirect_location;
        \Alert::success("Posted Successfully!")->flash();
        return redirect()->back();
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
        $post = OnlinePost::where('id', $id)->first();

        $this->crud->hasAccessOrFail('delete');
        event(new PostCrudEvent($post, 'Delete'));

        /**
         * Delete The Notification
         */
        $notifications = DB::table('notifications')->where('type', 'App\Notifications\OnlinePostPublishedNotification')->get();
        $notifications = collect($notifications)->map(function ($notification, $key) use ($post) {
            $online_post_id = json_decode($notification->data)->id;
            $noty_data_type = json_decode($notification->data)->type;

            if($online_post_id == $post->id && $noty_data_type == 'online-post') {
                DB::table('notifications')->where('id', $notification->id)->delete();
            }
        });

        return $this->crud->delete($id);
    }

    public function getClass($class_code)
    {
        $employee_id = backpack_auth()->user()->employee_id;
        if(backpack_auth()->user()->hasRole('School Head')){

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
            // if($class) {
            //     $class->conference_status = OnlineClass::getConferenceStatus($class->code);
            // }
            return $class;
        }
        else
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
            if($class) {
                // $class->conference_status = OnlineClass::getConferenceStatus($class->code);
                
                if($class->teacher_id != backpack_auth()->user()->employee_id) {
                    if($class->substitute_teachers){
                        if(count($class->substitute_teachers)>0){
                            if(!in_array(backpack_auth()->user()->employee_id, $class->substitute_teachers)){
                                return null;
                            } else {
                                $class->isTeacherSubstitute = 1;
                            }
                        } else{
                            return null;
                        }
                    } else {
                        return null;
                    }
                }
            }
            return $class;
        }
        return null;
    }

    public function getVideoConferenceStatus()
    {
        if(isset($this->data['my_classes'])){
            $classes = $this->data['my_classes'];
        }
        else{
            $classes = $this->getMyClasses();
        }
        $status = [];

        if($classes){
           if(count($classes) > 0){
                foreach ($classes as $class) {
                    $meetingId = $class->code;
                    $password = "teacher-" . $class->code;
                    $video_conference_info = BBB::getConferenceStatus($meetingId, $password);
                    $status[] = [
                        'meetingId' => $class->code,
                        'data' => gettype($video_conference_info) == "object" ? $video_conference_info : null
                    ];
                }
            } 
        }
        return $status;
    }

    public function getMyClasses()
    {
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
        return null;
    }

    public function getClassPosts(Request $request)
    {
        // $this->data['class']  =   $this->onlineClass(request()->class_code);
        $posts = $this->data['posts']->paginate(10);
        $posts->setPath(url()->current());   
        return response()->json(['posts' => $posts]);
    }

    public function likePost(Request $request)
    {
        $employee_id    =   backpack_auth()->user()->employee_id;
        $response       =   [
            'error'         =>  false,
            'message'       =>  null,
            'data'          =>  null
        ];
        $newLike        =   [
            'type'          =>  'employee',
            'student_id'    =>  null,
            'employee_id'   =>  $employee_id
        ];
        $post           =   OnlinePost::with('comments', 'comments.commentable')
                                    ->where('online_class_id', $this->class->id)
                                    ->where('id', \Route::current()->parameter('post_id'))
                                    ->where('id', $request->post_id)
                                    ->first();
        $postLikes      =   collect($post->likes)->toArray();

        if(!$post)
        {
            $response['error']   = true;
            $response['title']   = 'Error';
            $response['message'] = 'Error, Post not found.';
            return $response;
        }
        
        $employeeLikes      =   $post->employee_likes;

        if(!in_array($employee_id, $employeeLikes)){
            // array_push($postLikes, $newLike);
            $post->likes = collect($post->likes)->push($newLike);
        }
        else
        {
            $post->likes = array_values(collect($post->likes)->where('employee_id', '!=', $employee_id)->toArray());
        }
        $post->update();

        $response['title']  = 'Success';
        $response['data']   = $post;
        event(new PostCrudEvent($post, 'Like'));

        return $response;
    }

    public function storeComment(Request $request)
    {
        $response       =   [
            'error'         =>  false,
            'message'       =>  null,
            'data'          =>  null
        ];

        // Get The Post
        $post       =   OnlinePost::where('online_class_id', $this->class->id)
                                    ->where('id', \Route::current()->parameter('post_id'))
                                    ->where('id', $request->post_id)
                                    ->first();

        if(!$post)
        {
            $response['error']      =   true;
            $response['title']      =   'Error';
            $response['message']    =   'Error, Something Went Wrong, Please Try To Reload The Page.';
            return $response;
        }

        // Store The Comment as Employee
        $comment    =   new OnlineComment();
        $comment->content           =   $request->content; 
        $comment->online_post_id    =   $post->id;
        $comment->commentable_id    =   backpack_auth()->user()->employee_id;
        $comment->commentable_type  =   'App\Models\Employee';
        $comment->save();

        $comment    = OnlineComment::with('commentable')->where('id', $comment->id)->first();
        event(new CommentCrudEvent($comment, 'create'));
        
        $response['title']  = 'Success';
        return $response;
    }

    public function deleteComment(Request $request)
    {
        $response       =   [
            'error'         =>  false,
            'message'       =>  null,
            'data'          =>  null
        ];

        // Get The Post
        $comment        =   OnlineComment::with('post', 'commentable')
                                ->where('online_post_id', \Route::current()->parameter('post_id'))
                                ->where('id', \Route::current()->parameter('comment_id'))
                                ->where('online_post_id', $request->post_id)
                                ->where('id', $request->comment_id)
                                ->first();

        if(!$comment)
        {
            $response['error']      =   true;
            $response['title']      =   'Error';
            $response['message']    =   'Error, Something Went Wrong, Please Try To Reload The Page.';
            return $response;
        }

        if(!$comment->post && !$comment->commentable)
        {
            $response['error']      =   true;
            $response['title']      =   'Error';
            $response['message']    =   'Error, Something Went Wrong, Please Try To Reload The Page.';
            return $response;
        }

        if($comment->post->online_class_id != $this->class->id)
        {
            $response['error']      =   true;
            $response['title']      =   'Error';
            $response['message']    =   'Error, Something Went Wrong, Please Try To Reload The Page.';
            return $response;
        }

        if($comment->post->teacher_id != backpack_auth()->user()->employee->id && $comment->commentable->employee_id != backpack_auth()->user()->employee->employee_id)
        {
            $response['error']      =   true;
            $response['title']      =   'Error';
            $response['message']    =   'Error, Something Went Wrong, Please Try To Reload The Page.';
            return $response;
        }

        event(new CommentCrudEvent($comment, 'delete'));
        // Delete The Comment
        $comment->delete();
        
        $response['title']  = 'Success';
        return $response;
    }
}
