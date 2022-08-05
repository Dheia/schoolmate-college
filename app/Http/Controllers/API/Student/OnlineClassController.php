<?php

namespace App\Http\Controllers\API\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;

// Events
use App\Events\PostCrudEvent;
use App\Events\CommentCrudEvent;

// MODELS
use App\Models\Student;
use App\Models\SchoolYear;
use App\Models\Enrollment;

use App\Models\OnlineClass;
use App\Models\OnlinePost;
use App\Models\OnlineComment;
use App\Models\OnlineCourse;
use App\Models\OnlineClassModule;
use App\Models\OnlineClassTopic;
use App\Models\OnlineTopicPage;
use App\Models\StudentSectionAssignment;

use App\Models\Assignment;
use App\Models\StudentSubmittedAssignment;

class OnlineClassController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | ONLINE CLASSES
    |--------------------------------------------------------------------------
    */
    public function onlineClasses()
    {
        $student            =   request()->user()->student;
        $schoolYear         =   SchoolYear::active()->first();
        $online_classes     =   self::getOnlineClasses($student, $schoolYear);

        return response()->json(['online_classes' => $online_classes]);
    }

    /*
    |--------------------------------------------------------------------------
    | ONLINE CLASS (NEED CLASS CODE)
    |--------------------------------------------------------------------------
    */
    public function onlineClass($class_code)
    {
        $response   = [
            'status'    => null,
            'data'      => null,
            'message'   => null
        ];

        $student        =   request()->user()->student;
        $schoolYear     =   SchoolYear::active()->first();
        $online_classes =   self::getOnlineClasses($student, $schoolYear);

        $class          =   OnlineClass::where('code', $class_code)
                                ->notArchive()
                                ->active()
                                ->first();

        if(! $class) {
            $response['status']  = 'error';
            $response['message'] = 'Class Not Found';
            return response()->json($response);
        }

        if(! in_array($class->id, $online_classes->pluck('id')->toArray())) {
            $response['status']  = 'error';
            $response['message'] = 'Mismatch Class!';
            return response()->json($response);
        }

        $response['status']  = 'success';
        $response['message'] = 'Class has been fetched successfully.';
        $response['data']    = $class;

        return response()->json($response);
    }

    /*
    |--------------------------------------------------------------------------
    | ONLINE CLASS STUDENT LIST (NEED CLASS CODE)
    | // Validate Via Middleware CheckStudentClass
    |--------------------------------------------------------------------------
    */
    public function studentList($class_code)
    {
        $request        = request();

        $response   = [
            'status'    => $request->response_status, // Data from Middleware CheckStudentClass
            'data'      => $request->response_data,
            'message'   => $request->response_message
        ];

        if($request->response_status == "error") {
            return response()->json($response);
        }

        $student        =   request()->user()->student;
        $online_class   =   $request->class;
        $student_list   =   null;

        if($online_class->activeStudentSectionAssignment)
        {
            if(count(json_decode($online_class->activeStudentSectionAssignment->students))>0)
            {
                $fields = [
                    'id', 'lastname', 'firstname', 'middlename', 'gender', 'photo', 'birthdate',
                    'schoolyear', 'department_id', 'level_id', 'track_id'
                ];
                $student_list   =   Student::whereIn('studentnumber', json_decode($online_class->activeStudentSectionAssignment->students))
                                        ->where('studentnumber', '!=', $student->studentnumber)
                                        ->orderBy('gender', 'ASC')
                                        ->orderBy('lastname', 'ASC')
                                        ->orderBy('firstname', 'ASC')
                                        ->orderBy('middlename', 'ASC')
                                        ->select($fields)
                                        ->get();
            }
        }

        return response()->json($student_list);
    }

    /*
    |--------------------------------------------------------------------------
    | ONLINE CLASSES POSTS
    |--------------------------------------------------------------------------
    */
    public function onlineClassesPosts()
    {
        $student            =   request()->user()->student;
        $schoolYear         =   SchoolYear::active()->first();
        $online_classes     =   self::getOnlineClasses($student, $schoolYear);

        $posts              =   OnlinePost::whereIn('online_class_id', $online_classes->pluck('id'))
                                    ->notArchive()
                                    ->active()
                                    ->orderBy('created_at', 'DESC')
                                    ->with('comments')
                                    ->paginate(10);

        return response()->json($posts);
    }

    /*
    |--------------------------------------------------------------------------
    | ONLINE CLASS POSTS (NEED CLASS CODE)
    |--------------------------------------------------------------------------
    */
    public function onlineClassPosts($class_code)
    {
        $response   = [
            'status'    => null,
            'data'      => null,
            'message'   => null
        ];

        $student        =   request()->user()->student;
        $schoolYear     =   SchoolYear::active()->first();
        $online_classes =   self::getOnlineClasses($student, $schoolYear);

        /* Get Class */
        $class_response =   self::getOnlineClass($class_code, $online_classes);

        if(! is_array($class_response)) {
            $class_response = $class_response->getData();
        } else {
            $class_response = (object)$class_response;
        }

        $online_class   =   $class_response->data;

        if($class_response->status != 'success' || $online_class == null) {
            $class_response->status = 'error';
            return response()->json($class_response);
        }

        /* Get Class Posts */
        $columns = ['id', 'content', 'files', 'file_name', 'teacher_id', 'studentnumber', 'likes', 'active', 'archive', 'created_at'];
        $posts  =   OnlinePost::where('online_class_id', $online_class->id)->notArchive()
                        ->active()
                        ->orderBy('created_at', 'DESC')
                        ->with('comments')
                        ->select($columns)
                        ->paginate(10);

        $response['status']   = 'success';
        $response['message']  = $posts->total() > 0 ? $posts->total() . ' Total Posts.' : 'No Posts Yet.';
        $response['data']     = $posts;

        return response()->json($response);
    }

    /*
    |--------------------------------------------------------------------------
    | ONLINE CLASS SINGLE POST (NEED CLASS CODE && post_id)
    |--------------------------------------------------------------------------
    */
    public function onlinePost($post_id)
    {
        $response   = [
            'status'    => null,
            'data'      => null,
            'message'   => null
        ];

        $class_code     =   request()->class_code;

        $user           =   request()->user();
        $student        =   request()->user()->student;
        $schoolYear     =   SchoolYear::active()->first();
        $online_classes =   self::getOnlineClasses($student, $schoolYear);

        /* Get Class */
        $class_response =   self::getOnlineClass($class_code, $online_classes);

        if(! is_array($class_response)) {
            $class_response = $class_response->getData();
        } else {
            $class_response = (object)$class_response;
        }

        $online_class   =   $class_response->data;

        if($class_response->status != 'success' || $online_class == null) {
            $class_response->status = 'error';
            return response()->json($class_response);
        }

        /* Get Post with Comments */
        $post = OnlinePost::with('comments')->where('online_class_id', $online_class->id)->where('id', $post_id)->first();

        if(! $post) {
            $response['status']  = 'error';
            $response['message'] = 'Post Not Found';
            return response()->json($response);
        }

        $response['status']   = 'success';
        $response['message']  = 'Post has been fetched successfully.';
        $response['data']     = $post;

        /* Mark User Notification As Read */
        $notification_id      = request()->notification_id;
        if($notification_id) {

            $notification = $user->unreadNotifications()->where('id', $notification_id)->first();

            if($notification) {
                $notification->read_at = now();
                $notification->save();
            }
        }

        return response()->json($response);
    }

    /*
    |--------------------------------------------------------------------------
    | LIKE THE ONLINE CLASS POST
    |--------------------------------------------------------------------------
    */
    public function likePost($post_id)
    {
        $response   = [
            'status'    => null,
            'data'      => null,
            'message'   => null
        ];

        $student        =   request()->user()->student;
        $schoolYear     =   SchoolYear::active()->first();
        $online_classes =   self::getOnlineClasses($student, $schoolYear);

        $newLike    =   [
            'type'          =>  'student',
            'student_id'    =>  $student->id,
            'employee_id'   =>  null
        ];

        $post = OnlinePost::where('id', $post_id)->first();

        if(! $post) {
            $response['status']  = 'error';
            $response['message'] = 'Post Not Found!';
            return response()->json($response);
        }

        /* Check Post Class */
        $online_class = $post->class;

        if(! $online_class) {
            $response['status']  = 'error';
            $response['message'] = 'Class Not Found!';
            return response()->json($response);
        }

        if(! in_array($online_class->id, $online_classes->pluck('id')->toArray())) {
            $response['status']  = 'error';
            $response['message'] = 'Mismatch Class!';
            return response()->json($response);
        }

        $postLikes  =   collect($post->likes)->toArray();

        $studentLikes      =   collect($post->likes)->where('type', 'student')->pluck('student_id')->toArray();

        if(!in_array($student->id, $studentLikes)){
            $post->likes = collect($post->likes)->push($newLike);
            $response['data']['is_liked'] = true;
            $response['message'] = 'Post has been liked successfully.';
        }
        else
        {
            $post->likes = array_values(collect($post->likes)->where('student_id', '!=', $student->id)->toArray());
            $response['data']['is_liked'] = false;
            $response['message'] = 'Post has been unliked successfully.';
        }

        $post->update();

        $response['title'] = 'success';
        $response['data']['post'] = $post;
        event(new PostCrudEvent($post, 'Like'));

        return $response;
    }

    /*
    |--------------------------------------------------------------------------
    | COMMENT IN THE ONLINE CLASS POST
    |--------------------------------------------------------------------------
    */
    public function storeComment($post_id)
    {
        $response   = [
            'status'    => null,
            'data'      => null,
            'message'   => null
        ];

        $student        =   request()->user()->student;
        $schoolYear     =   SchoolYear::active()->first();
        $online_classes =   self::getOnlineClasses($student, $schoolYear);

        $post = OnlinePost::where('id', $post_id)->first();

        if(! $post) {
            $response['status']  = 'error';
            $response['message'] = 'Post Not Found!';
            return response()->json($response);
        }

        /* Check Post Class */
        $online_class = $post->class;

        if(! $online_class) {
            $response['status']  = 'error';
            $response['message'] = 'Class Not Found!';
            return response()->json($response);
        }

        if(! in_array($online_class->id, $online_classes->pluck('id')->toArray())) {
            $response['status']  = 'error';
            $response['message'] = 'Mismatch Class!';
            return response()->json($response);
        }

        // Store The Comment as Student
        $comment    =   new OnlineComment();
        $comment->content           =   request()->content; 
        $comment->online_post_id    =   $post->id;
        $comment->commentable_id    =   $student->id;
        $comment->commentable_type  =   'App\Models\Student';
        $comment->save();

        $comment    = OnlineComment::with('commentable')->where('id', $comment->id)->first();
        event(new CommentCrudEvent($comment, 'create'));

        $response['title'] = 'success';
        $response['data']  = $comment;

        return $response;
    }

    /*
    |--------------------------------------------------------------------------
    | COMMENT IN THE ONLINE CLASS POST
    |--------------------------------------------------------------------------
    */
    public function deleteComment($post_id, $comment_id)
    {
        $response   = [
            'status'    => null,
            'data'      => null,
            'message'   => null
        ];

        $student        =   request()->user()->student;
        $schoolYear     =   SchoolYear::active()->first();
        $online_classes =   self::getOnlineClasses($student, $schoolYear);

        $post = OnlinePost::where('id', $post_id)->first();

        if(! $post) {
            $response['status']  = 'error';
            $response['message'] = 'Post Not Found!';
            return response()->json($response);
        }

        /* Check Post Class */
        $online_class = $post->class;

        if(! $online_class) {
            $response['status']  = 'error';
            $response['message'] = 'Class Not Found!';
            return response()->json($response);
        }

        if(! in_array($online_class->id, $online_classes->pluck('id')->toArray())) {
            $response['status']  = 'error';
            $response['message'] = 'Mismatch Class!';
            return response()->json($response);
        }

        $comment = OnlineComment::where('id', $comment_id)->where('online_post_id', $post_id)->first();

        if(! $comment) {
            $response['status']  = 'error';
            $response['message'] = 'Comment Not Found!';
            return response()->json($response);
        }

        $response['data']  = $comment;

        event(new CommentCrudEvent($comment, 'delete'));

        // Delete The Comment
        $comment->delete();
        
        $response['status']  = 'success';
        $response['message'] = 'Comment has been deleted successfully.';
        return $response;
    }

    /*
    |--------------------------------------------------------------------------
    | ONLINE ASSIGNMENTS
    |--------------------------------------------------------------------------
    */
    public function onlineAssignments()
    {
        $student                =   request()->user()->student;
        $schoolYear             =   SchoolYear::active()->first();

        $online_classes         =   self::getOnlineClasses($student, $schoolYear);
        $studentsAssignments    =   StudentSubmittedAssignment::where('student_id', $student->id)->get();
        $assignments            =   Assignment::whereIn('online_class_id', $online_classes->pluck('id'))
                                        ->whereNotIn('id', $studentsAssignments->pluck('assignment_id'))
                                        ->orderBy('due_date', 'DESC')
                                        ->get();

        return response()->json(['assignments' => $assignments]);
    }

    /*
    |--------------------------------------------------------------------------
    | ONLINE CLASS ASSIGNEMNTS (NEED CLASS CODE)
    |--------------------------------------------------------------------------
    */
    public function onlineClassAssigments($class_code)
    {
        $response   = [
            'status'    => null,
            'data'      => null,
            'message'   => null
        ];

        $student        =   request()->user()->student;
        $schoolYear     =   SchoolYear::active()->first();
        $online_classes =   self::getOnlineClasses($student, $schoolYear);

        /* Get Class */
        $class_response =   self::getOnlineClass($class_code, $online_classes);

        if(! is_array($class_response)) {
            $class_response = $class_response->getData();
        } else {
            $class_response = (object)$class_response;
        }
        
        $online_class   =   $class_response->data;

        if($class_response->status != 'success' || $online_class == null) {
            $class_response->status = 'error';
            return response()->json($class_response);
        }

        /* Get Class Assignments */
        $studentsAssignments    =   StudentSubmittedAssignment::where('student_id', $student->id)->get();
        $assignments            =   Assignment::where('online_class_id', $online_class->id)
                                        ->whereNotIn('id', $studentsAssignments->pluck('assignment_id'))
                                        ->orderBy('due_date', 'DESC')
                                        ->paginate(10);

        $response['status']   = 'success';
        $response['message']  = $assignments->total() > 0 ? $assignments->total() . ' Total Assignments.' : 'No Assignments Yet.';
        $response['data']     = $assignments;

        return response()->json($response);
    }

    /*
    |--------------------------------------------------------------------------
    | ONLINE CLASS - COURSE (NEED CLASS CODE)
    |--------------------------------------------------------------------------
    */
    public function onlineClassCourse($class_code)
    {
        $response   = [
            'status'    => null,
            'data'      => null,
            'message'   => null
        ];

        $student        =   request()->user()->student;
        $schoolYear     =   SchoolYear::active()->first();
        $online_classes =   self::getOnlineClasses($student, $schoolYear);

        /* Get Class */
        $class_response =   self::getOnlineClass($class_code, $online_classes);

        if(! is_array($class_response)) {
            $class_response = $class_response->getData();
        } else {
            $class_response = (object)$class_response;
        }

        $online_class   =   $class_response->data;

        if($class_response->status != 'success' || $online_class == null) {
            $class_response->status = 'error';
            return response()->json($class_response);
        }

        /* Get Class Course */
        if(! $online_class->online_course_id) {
            $response['status']   = 'success';
            $response['message']  = 'No Course Assigned.';
            return response()->json($response);
        }

        $class_course   =   OnlineCourse::where('id', $online_class->online_course_id)->first();

        $response['status']   = $class_course ? 'success' : 'error';
        $response['message']  = $class_course ? 'Class Course has been fetched successfully.' : 'Assigned Course Not Found.';
        $response['data']     = $class_course ? $class_course : null;

        return response()->json($response);
    }

    /*
    |--------------------------------------------------------------------------
    | ONLINE COURSE - MODULES
    |--------------------------------------------------------------------------
    */
    public function onlineCourseModules($class_code)
    {
        $response   = [
            'status'    => null,
            'data'      => null,
            'message'   => null
        ];

        /* Get Class Course */
        $class_course_response = self::onlineClassCourse($class_code);
        
        if(! is_array($class_course_response)) {
            $class_course_response = $class_course_response->getData();
        } else {
            $class_course_response = (object)$class_course_response;
        }

        $class_course = $class_course_response->data;

        if($class_course_response->status != 'success' || $class_course == null) {
            $class_course_response->status = 'error';
            return response()->json($class_course_response);
        }

        /* Get Course Modules with Topics*/
        $course_modules =   OnlineClassModule::where('online_course_id', $class_course->id)
                                ->active()
                                ->notArchive()
                                ->paginate(10);

        $response['status']     =   'success';
        $response['message']    =   $course_modules->total() > 0 ? $course_modules->total() . ' Total Modules.' : 'No Module Yet.';
        $response['data']       =   $course_modules;

        return response()->json($response);
    }

    /*
    |--------------------------------------------------------------------------
    | ONLINE COURSE - MODULE TOPICS
    |--------------------------------------------------------------------------
    */
    public function onlineCourseTopics($class_code, $module_id)
    {
        $response   = [
            'status'    => null,
            'data'      => null,
            'message'   => null
        ];

        /* Get Class Course */
        $class_course_response = self::onlineClassCourse($class_code);
        
        if(! is_array($class_course_response)) {
            $class_course_response = $class_course_response->getData();
        } else {
            $class_course_response = (object)$class_course_response;
        }

        $class_course = $class_course_response->data;

        if($class_course_response->status != 'success' || $class_course == null) {
            $class_course_response->status = 'error';
            return response()->json($class_course_response);
        }

        /* Get Course Module*/
        $course_module  =   OnlineClassModule::where('online_course_id', $class_course->id)
                                ->where('id', $module_id)
                                ->active()
                                ->notArchive()
                                ->first();
                    
        if(! $course_module) {
            $response['status']   = 'error';
            $response['message']  = 'Module Not Found.';
            return response()->json($response);
        }

        /* Get Module Topics*/
        $module_topics  =   $course_module->topics;

        /* Response Data (Course Module && Module Topics) */
        $data = [
            'course_module' => $course_module,
            'module_topics' => $module_topics,
        ];

        $response['status']     =   'success';
        $response['message']    =   $module_topics->count() > 0 ? $module_topics->count() . ' Total Topics.' : 'No Topic Yet.';
        $response['data']       =   $data;

        return response()->json($response);
    }

    /*
    |--------------------------------------------------------------------------
    | ONLINE COURSE - TOPIC PAGES
    |--------------------------------------------------------------------------
    */
    public function onlineCourseTopicPages($class_code, $module_id, $topic_id)
    {
        $response   = [
            'status'    => null,
            'data'      => null,
            'message'   => null
        ];

        /* Get Class Course */
        $class_course_response = self::onlineClassCourse($class_code);

        if(! is_array($class_course_response)) {
            $class_course_response = $class_course_response->getData();
        } else {
            $class_course_response = (object)$class_course_response;
        }

        $class_course = $class_course_response->data;

        if($class_course_response->status != 'success' || $class_course == null) {
            $class_course_response->status = 'error';
            return response()->json($class_course_response);
        }

        /* Get Course Modules */
        $course_module  =   OnlineClassModule::where('online_course_id', $class_course->id)
                                ->where('id', $module_id)
                                ->active()
                                ->notArchive()
                                ->select('id', 'title', 'description')
                                ->first();

        if(! $course_module) {
            $response['status']   = 'success';
            $response['message']  = 'Module Not Found.';
            return $response;
        }

        /* Get Module Topic */
        $module_topic   =   OnlineClassTopic::where('id', $topic_id)
                                ->where('online_course_id', $class_course->id)
                                ->where('online_class_module_id', $course_module->id)
                                ->first();

        if(! $module_topic) {
            $response['status']   = 'error';
            $response['message']  = 'Topic Not Found.';
            return $response;
        }

        /* Get Topic Pages */
        $topic_pages    =   $module_topic->pages;

        /* Response Data (Module Topic && Topic Pages) */
        $data = [
            'module_topic'  => $module_topic,
            'topic_pages'   => $topic_pages
        ];

        $response['status']     =   'success';
        $response['message']    =   $topic_pages->count() > 0 ? $topic_pages->count() . ' Total Pages.' : 'No Page Yet.';
        $response['data']       =   $data;

        return response()->json($response);
    }

    /*
    |--------------------------------------------------------------------------
    | GET ONLINE CLASSES
    |--------------------------------------------------------------------------
    */
    public function getOnlineClasses($student, $school_year)
    {
        $student_sections   =   StudentSectionAssignment::where('school_year_id', $school_year->id)
                                    ->whereJsonContains('students', $student->studentnumber)
                                    ->get();

        $online_classes     =   OnlineClass::where('school_year_id', $school_year->id)
                                    ->whereIn('section_id', $student_sections->pluck('section_id'))
                                    ->active()
                                    ->notArchive()
                                    ->orderBy('id', 'DESC')
                                    ->get();
        return $online_classes;
    }

    /*
    |--------------------------------------------------------------------------
    | GET SINGLE ONLINE CLASS
    |--------------------------------------------------------------------------
    */
    public function getOnlineClass($class_code, $online_classes)
    {
        $response   = [
            'status'    => null,
            'data'      => null,
            'message'   => null
        ];

        $class          =   OnlineClass::where('code', $class_code)
                                ->notArchive()
                                ->active()
                                ->first();

        if(! $class) {
            $response['status']  = 'error';
            $response['message'] = 'Class Not Found';
            return response()->json($response);
        }

        /* Validate If The Class Is Belong To Student's Online Classes */
        if(! in_array($class->id, $online_classes->pluck('id')->toArray())) {
            $response['status']  = 'error';
            $response['message'] = 'Mismatch Class!';
            return response()->json($response);
        }

        $response['status']  = 'success';
        $response['message'] = 'Class has been fetched successfully.';
        $response['data']    = $class;
        return response()->json($response);
    }
}
