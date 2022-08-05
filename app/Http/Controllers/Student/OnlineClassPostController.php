<?php

namespace App\Http\Controllers\Student;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\Student\OnlinePostStudentRequest as StoreRequest;
use App\Http\Requests\Student\OnlinePostStudentRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class OnlinePostCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */

use Symfony\Component\HttpFoundation\Request;

// Events
use App\Events\PostCrudEvent;
use App\Events\CommentCrudEvent;

// MODELS
use App\Models\OnlineClass;
use App\Models\OnlineClassAttendance;
use App\Models\OnlinePost;
use App\Models\OnlineComment;

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

use App\Models\StudentSectionAssignment;
use App\Models\Enrollment;
use App\Models\Student;

use App\Models\QuipperStudentAccount;

use App\Http\Controllers\BBB;

use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\GetRecordingsParameters;

class OnlineClassPostController extends CrudController
{
    public $class           = null;
    public $student         = null;
    public $student_section = null;
    public $online_class    = null;
    public $school_year     = null;

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | Get The Student Information
        |--------------------------------------------------------------------------
        */
        $this->student          =   $student                    =   auth()->user()->student;
        $this->studentSubmittedQuiz = $student->submittedQuizzes->pluck('online_class_quiz_id')->toArray();
        $this->school_year      =   SchoolYear::active()->first();
        $student_section        =   $this->studentSectionAssignment();
        $onlineClasses          =   $this->onlineClasses();
        $posts                  =   null;

        /*
        |--------------------------------------------------------------------------
        | Mark User Notification As Read
        |--------------------------------------------------------------------------
        */
        $notification_id = request()->notification_id;
        
        if($notification_id) {

            $user           = auth()->user();
            $notification   = $user->unreadNotifications()->where('id', $notification_id)->first();

            if($notification) {
                $notification->read_at = now();
                $notification->save();
            }
        }

        if(request()->class_code){
            $posts                  =   $this->showClassPosts(request()->class_code);
            $this->data['class']    =   $online_class   =   $this->onlineClass(request()->class_code);
            $this->class            =   $online_class;
            
            abort_if(!$this->data['class'], 404, 'Class Not Found.');
            abort_if(!in_array($online_class->section_id, collect($student_section)->pluck('section_id')->toArray()), 403, 'Mismatch Class');
            abort_if(!in_array($online_class->term_type, collect($student_section)->pluck('term_type')->toArray()), 403, 'Mismatch Class');
            abort_if(!in_array($online_class->summer, collect($student_section)->pluck('summer')->toArray()), 403, 'Mismatch Class');

            $this->data['class_attendance'] =   OnlineClassAttendance::getStudentAttendanceToday($online_class->id, $student->id);
            
            $enrollment                 =   $student->enrollments->where('school_year_id', $online_class->school_year_id)
                                                ->where('term_type', $online_class->term_type)
                                                ->where('is_applicant', 0)
                                                ->first();

            $this->data['enrollment']   =   $enrollment;

            $this->crud->addField([ // select_from_array
                'name' => 'online_class_id',
                'label' => "",
                'type' => 'hidden',
                'value' => $this->data['class']->id
            ]);
        }
        else{
            $posts  =   OnlinePost::with('subject', 'section', 'class', 'comments', 'comments.commentable', 'classQuiz', 'classQuiz.quiz')
                                ->whereIn('online_class_id', collect($onlineClasses)->pluck('id')->toArray())
                                ->orderBy('created_at', 'DESC')
                                ->notArchive()
                                ->active()
                                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\OnlinePost');
        $this->crud->setRoute(config('backpack.base.') . 'student/online-post');
        $this->crud->setEntityNameStrings('Online Class', 'Online Class');

        $this->data['onlineClasses']            =   $onlineClasses;
        $this->data['my_classes']               =   $onlineClasses;
        $this->data['school_year']              =   $this->school_year;
        $this->data['posts']                    =   $posts;
        $this->data['user']                     =   auth()->user()->student;
        $this->data['studentSubmittedQuiz']     =   $student->submittedQuizzes->pluck('online_class_quiz_id')->toArray();

        // $this->data['quipperAccount']           =   QuipperStudentAccount::where('student_id', auth()->user()->student->id)->first();

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

        if(request()->class_code){
            $this->crud->setListView('student.online-class.class-posts');
            $this->crud->setCreateView('student.online-class.class-posts');
            $this->crud->setEditView('student.online-class.class-posts');
        }
        else {
            $this->crud->setListView('student.online-class.home');
            $this->crud->setCreateView('student.online-class.home');
            $this->crud->setEditView('student.online-class.home');
        }
        // $this->data['teacher'] = User::where('employee_id', request()->get('teacher_id'))->first();

        // $this->data['class'] = OnlineClass::with('subject', 'section')->where('code', request()->get('class_code'))->first();

        // dd($posts);
        /*
        |--------------------------------------------------------------------------
        | FIELDS
        |--------------------------------------------------------------------------
        */
        $this->crud->addField([ // select_from_array
                'name' => 'studentnumber',
                'label' => "",
                'type' => 'hidden',
                'value' => $this->student->studentnumber
            ]);
        $this->crud->addField([   // Textarea
            'name' => 'content',
            'label' => '',
            'type' => 'textarea',
            'attributes' => [
               'placeholder' => 'Type your note here...',
               'id'          => 'content'
            ],
        ]);

        if(!request()->class_code){
            $this->crud->addField([ // select_from_array
                'name' => 'online_class_id',
                'label' => "Select Group",
                'type' => 'select_from_array',
                'options' => [],
                'allows_null' => false,
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ]);
        }
        $this->crud->addField([
            'name' => 'filessq',
            'label' => '',
            'type' => 'onlineClass.files',
            'upload' => true,
            'disk' => 'uploads'
        ]);

        /*
        |--------------------------------------------------------------------------
        | SCRIPT
        |--------------------------------------------------------------------------
        */
        $this->crud->addField([   // Textarea
            'name' => 'script',
            'label' => '',
            'type' => 'onlineClass.script'
        ]);
        $this->crud->addClause('where', 'school_year_id', $this->school_year->id);
        
        // $this->crud->addClause('where', 'teacher_id', request()->teacher_id);
        // $this->crud->addClause('where', 'online_class_id', $this->class->id);
         $this->data['imageExtensions'] = array('jpg'=>'jpg', 'jpeg'=>'jpeg', 'gif'=>'gif', 'png'=>'png', 'bmp'=>'bmp', 'svg'=>'svg', 'svgz'=>'svgz', 'cgm'=>'cgm', 'djv'=>'djv', 'djvu'=>'djvu', 'ico'=>'ico', 'ief'=>'ief','jpe'=>'jpe', 'pbm'=>'pbm', 'pgm'=>'pgm', 'pnm'=>'pnm', 'ppm'=>'ppm', 'ras'=>'ras', 'rgb'=>'rgb', 'tif'=>'tif', 'tiff'=>'tiff', 'wbmp'=>'wbmp', 'xbm'=>'xbm', 'xpm'=>'xpm', 'xwd'=>'xwd');
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW SINGLE POST
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        if(! $this->class) {
            \Alert::warning("Class Not Found.")->flash();
            // abort(404, 'Post Not Found.');
            return redirect()->back();
        }

        $this->data['post'] = OnlinePost::where('id', $id)->where('online_class_id', $this->class->id)->first();

        if(! $this->data['post']) {
            \Alert::warning("Post Not Found.")->flash();
            // abort(404, 'Post Not Found.');
            return redirect()->back();
        }

        /* Mark User Notification As Read */
        $notification_id = request()->notification_id;
        
        if($notification_id) {

            $user           = auth()->user();
            $notification   = $user->unreadNotifications()->where('id', $notification_id)->first();

            if($notification) {
                $notification->read_at = now();
                $notification->save();
            }
        }

        // dd($this->data);
        return view('student.online-class.class-post-show', $this->data);
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */
    public function store(StoreRequest $request)
    {
        $class = OnlineClass::find($request->online_class_id)->first();
        if(!$class)
        {
            abort(404, 'Class Not Found.');
        }
        $request->request->set('subject_id', $class->subject_id);
        $request->request->set('section_id', $class->section_id);
        $request->request->set('school_year_id', $class->school_year_id);
        $request->request->set('term_type', $class->term_type);
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW CLASS POSTS
    |--------------------------------------------------------------------------
    */
    public function showClassPosts($code)
    {
        $this->student          =   $student                    =   auth()->user()->student;
        $student_section        =   $this->studentSectionAssignment();
        $this->student_section  =   $student_section;
        $this->online_class     =   $onlineClass                =   $this->onlineClass($code);

        if(!$this->online_class) {
            abort(403, 'Mismatch Class');
        }
        if(!in_array($this->online_class->section_id, collect($student_section)->pluck('section_id')->toArray())){
            abort(403, 'Mismatch Class');
        }
        $class_posts    =   $this->getClassPosts($this->online_class);

        return $class_posts;

    }

    /*
    |--------------------------------------------------------------------------
    | GET ONLINE CLASS
    |--------------------------------------------------------------------------
    */
    public function onlineClass($code)
    {
        $class      =   OnlineClass::with('course', 'subject', 'section', 'teacher', 'schoolYear')
                                    ->where('school_year_id', $this->school_year->id)
                                    ->where('code', $code)
                                    ->active()
                                    ->notArchive()
                                    ->first();
        // if($class) {
        //     $class->conference_status = OnlineClass::getConferenceStatus($class->code);
        // }
        return $class;
    }

    /*
    |--------------------------------------------------------------------------
    | GET CLASS POSTS
    |--------------------------------------------------------------------------
    */
    public function getClassPosts($class){
        $posts  =   OnlinePost::with('subject', 'section', 'class', 'comments', 'comments.commentable', 'classQuiz', 'classQuiz.quiz')
                                ->orderBy('created_at', 'DESC')
                                ->where('online_class_id', $class->id)
                                ->active()
                                ->notArchive()
                                ->get();
        return $posts;

    }

    /*
    |--------------------------------------------------------------------------
    | GET USER ONLINE CLASSES
    |--------------------------------------------------------------------------
    */
    public function onlineClasses()
    {
        $this->student      =   $student = auth()->user()->student;
        $classes            =   null;
        $student_section    =   $this->studentSectionAssignment();

        if( $student_section ?? '')
        {
            $classes    =   OnlineClass::where('school_year_id', SchoolYear::active()->first()->id)
                                    ->whereIn('section_id', collect($student_section)->pluck('section_id'))
                                    // ->whereIn('term_type', collect($student_section)->pluck('term_type'))
                                    // ->whereIn('summer', collect($student_section)->pluck('summer'))
                                    ->active()
                                    ->notArchive()
                                    ->get();
        }
        return $classes;
    }

    /*
    |--------------------------------------------------------------------------
    | GET VIDEO CONFERENCE STATUS
    |--------------------------------------------------------------------------
    */
    public function getVideoConferenceStatus()
    {
        $classes = $this->onlineClasses();
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

    /*
    |--------------------------------------------------------------------------
    | GET STUDENT SECTION ASSIGNMENT
    |--------------------------------------------------------------------------
    */
    public function studentSectionAssignment()
    {
        $student_section    =   [];
        if(!$this->school_year)
        {
             return [];
        }
        if(!$this->student){
            return [];
        }
        if(!$this->student->studentnumber){
            return [];
        }

        // Get All Sections Of Active School Year
        $studentSectionAssignments  =   StudentSectionAssignment::where('school_year_id', $this->school_year->id)->get();

        if(!$studentSectionAssignments){
            abort(403, "Account Section Not Yet Assigned.");
        }
        // Check User if in sections
        foreach ($studentSectionAssignments as $key => $studentSectionAssignment) {

            $students   = Student::whereIn('studentnumber', json_decode($studentSectionAssignment->students))
                                ->where('studentnumber', $this->student->studentnumber)
                                ->get();

            if(count($students) > 0){
                $student_section[] = $studentSectionAssignment;
            }
        }
        
        if(!$student_section){
            // return null;
            return [];
        }

        if(count($student_section) <= 0){
            return [];
        }

        return $student_section;
    }

    /*
    |--------------------------------------------------------------------------
    | GET POSTS
    |--------------------------------------------------------------------------
    */
    public function getPosts(Request $request)
    {
        // $this->data['class']  =   $this->onlineClass(request()->class_code);
        $posts = $this->data['posts']->paginate(10);
        $posts->setPath(url()->current());   
        return response()->json(['posts' => $posts]);
    }

    /*
    |--------------------------------------------------------------------------
    | GET SINGLE POST
    |--------------------------------------------------------------------------
    */
    public function getPost($id)
    {
        if(! $this->class) {
            \Alert::warning("Class Not Found.")->flash();
            // abort(404, 'Post Not Found.');
            return redirect()->back();
        }

        $post = OnlinePost::with('subject', 'section', 'class', 'comments', 'comments.commentable', 'classQuiz', 'classQuiz.quiz')
            ->where('online_class_id', $this->class->id)
            ->where('id', $id)
            ->first();
        return response()->json($post);
    }

    /*
    |--------------------------------------------------------------------------
    | STUDENT LIKE POST
    |--------------------------------------------------------------------------
    */
    public function likePost(Request $request)
    {
        $response   =   [
            'error'         =>  false,
            'message'       =>  null,
            'data'          =>  null
        ];

        $newLike    =   [
            'type'          =>  'student',
            'student_id'    =>  $this->student->id,
            'employee_id'   =>  null
        ];

        $post       =   OnlinePost::with('comments', 'comments.commentable')
                                    ->where('online_class_id', $this->class->id)
                                    ->where('id', \Route::current()->parameter('post_id'))
                                    ->where('id', $request->post_id)
                                    ->first();

        $postLikes  =   collect($post->likes)->toArray();

        if(!$post)
        {
            $response['error']   = true;
            $response['title']   = 'Error';
            $response['message'] = 'Error, Post not found.';
            return $response;
        }

        $studentLikes      =   collect($post->likes)->where('type', 'student')->pluck('student_id')->toArray();

        if(!in_array($this->student->id, $studentLikes)){
            $post->likes = collect($post->likes)->push($newLike);
        }
        else
        {
            $post->likes = array_values(collect($post->likes)->where('student_id', '!=', $this->student->id)->toArray());
        }

        $post->update();

        $response['title'] = 'Success';
        $response['data'] = $post;
        event(new PostCrudEvent($post, 'Like'));

        return $response;
    }

    /*
    |--------------------------------------------------------------------------
    | STORE USER COMMENT
    |--------------------------------------------------------------------------
    */
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

        // Store The Comment as Student
        $comment    =   new OnlineComment();
        $comment->content           =   $request->content; 
        $comment->online_post_id    =   $post->id;
        $comment->commentable_id    =   $this->student->id;
        $comment->commentable_type  =   'App\Models\Student';
        $comment->save();

        $comment    = OnlineComment::with('commentable')->where('id', $comment->id)->first();
        event(new CommentCrudEvent($comment, 'create'));
        
        $response['title']  = 'Success';
        return $response;
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE USER COMMENT
    |--------------------------------------------------------------------------
    */
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

        if($comment->post->studentnumber != $this->student->studentnumber && $comment->commentable->studentnumber != $this->student->studentnumber)
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
