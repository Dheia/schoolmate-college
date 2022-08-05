<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;

// MODELS
use App\Models\OnlineClass;
use App\Models\OnlineCourse;
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
use App\Models\OnlinePost;
use App\Models\Student;
use App\Models\OnlineClassModule;
use App\Models\OnlineClassTopic;
use App\Models\OnlineTopicPage;
use App\Models\OnlineClassStudentProgress;

use App\Models\Assignment;
use App\Models\StudentSubmittedAssignment;

use App\Models\QuipperStudentAccount;

use App\Http\Controllers\BBB;

use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\GetRecordingsParameters;

use Carbon\Carbon;
use App\Models\OnlineClassAttendance;

class OnlineClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $class           = null;
    public $student         = null;
    public $student_section = null;
    public $online_class    = null;
    public $class_attendance    = null;

    public function indexOld()
    {
        /*
        |--------------------------------------------------------------------------
        | Get The Student Information
        |--------------------------------------------------------------------------
        */
        $this->student          =   $student                    =   auth()->user()->student;

        $onlineClasses            =   null;
        $student_section    =   $this->studentSectionAssignment();

        if( $student_section ?? '')
        {
            $onlineClasses    =   OnlineClass::with('teacher')
                                    ->whereIn('section_id', collect($student_section)->pluck('section_id'))
                                    // ->whereIn('term_type', collect($student_section)->pluck('term_type'))
                                    // ->whereIn('summer', collect($student_section)->pluck('summer'))
                                    ->where('school_year_id', SchoolYear::active()->first()->id)
                                    ->notArchive()
                                    ->active()
                                    ->get();
        }   
        $my_classes    = $onlineClasses;

        $title = "My Classes";
        return view('student.online-class.class-dashboard', compact(['my_classes', 'title']));
    }

    public function index()
    {
        $title          =   "My Classes";
        /*
        |--------------------------------------------------------------------------
        | Get The Student Information
        |--------------------------------------------------------------------------
        */
        $this->student  =   $user   =   auth()->user()->student;
        $active_sy      =   SchoolYear::active()->first();
        $enrollment     =   Enrollment::where('studentnumber', $user->studentnumber)
                                ->where('school_year_id', $active_sy->id)
                                ->first();
        $department     =   $enrollment ? $enrollment->department : null;
        $term           =   $department ? $department->term : null;
        $term_types     =   $term ? $term->ordinal_terms: collect([]);

        $onlineClassController  =   new OnlineClassController();

        $student                =   auth()->user()->student;
        $student_section        =   $onlineClassController->studentSectionAssignment();
        $my_classes             =   $onlineClassController->getOnlineClasses();
        $section_ids            =   collect($student_section)->pluck('section_id')->toArray();
        $quipperAccount         =   QuipperStudentAccount::where('student_id', $student->id)->first();
        $studentsAssignments    =    StudentSubmittedAssignment::where('student_id', $student->id)->get();

        $assignments            =   Assignment::with('class')
                                        ->whereIn('online_class_id', $my_classes ? $my_classes->pluck('id') : [])
                                        ->whereNotIn('id', $studentsAssignments->pluck('assignment_id'))
                                        ->orderBy('due_date', 'DESC')
                                        ->get();
        $submittedAssignments   =   StudentSubmittedAssignment::where('student_id', $student->id)
                                            ->whereIn('assignment_id', $assignments->pluck('id'))
                                            ->get();

        return view('student.online-class.class-dashboard-2', compact(['title', 'user', 'term_types','assignments','submittedAssignments']));
    }

    public function showClassCourse($code) {
        $user                   =   auth()->user()->student;
        $this->student          =   $student                    =   auth()->user()->student;
        $student_section        =   $this->studentSectionAssignment();
        $this->student_section  =   $student_section;
        $this->online_class     =   $class                      =   $this->getOnlineClass($code);
        $my_classes             =   $this->getOnlineClasses();
        $section_ids            =   collect($student_section)->pluck('section_id')->toArray();

        if(!$this->online_class) {
            abort(403, 'Mismatch Class');
        }
        if(!in_array($this->online_class->section_id, $section_ids)){
            abort(403, 'Mismatch Class');
        }
        if(!$class->course)
        {
            abort(404, 'Class Course Not Found');
        }

        $enrollment                 =   $student->enrollments->where('school_year_id', $class->school_year_id)
                                                ->where('term_type', $class->term_type)
                                                ->where('is_applicant', 0)
                                                ->first();

        $class_attendance =   OnlineClassAttendance::getStudentAttendanceToday($class->id, $student->id);

        return view('student.online-class.class-course', compact(['my_classes', 'user', 'class', 'class_attendance', 'enrollment']));

    }

    public function showClassModules($code) {
        $this->student          =   $student                    =   auth()->user()->student;
        $student_section        =   $this->studentSectionAssignment();
        $this->student_section  =   $student_section;
        $this->online_class     =   $class                      =   $this->getOnlineClass($code);
        $my_classes             =   $this->getOnlineClasses();
        $section_ids            =   collect($student_section)->pluck('section_id')->toArray();

        if(!$this->online_class) {
            abort(403, 'Mismatch Class');
        }
        if(!in_array($this->online_class->section_id, $section_ids)){
            abort(403, 'Mismatch Class');
        }
        if(!$class->course)
        {
            abort(404, 'Class Course Not Found');
        }
        $modules = OnlineClassModule::with('course','course.modules', 'topics')->where('online_course_id', $class->course->id)->get();
        $user    = auth()->user()->student;


        $enrollment         =   $student->enrollments->where('school_year_id', $class->school_year_id)
                                    ->where('term_type', $class->term_type)
                                    ->where('is_applicant', 0)
                                    ->first();

        $class_attendance   =   OnlineClassAttendance::getStudentAttendanceToday($class->id, $student->id);

        return view('student.online-class.modules', compact(['modules', 'class', 'user', 'my_classes', 'class_attendance', 'enrollment']));
    }

    public function showClassModule($code, $module_id) {
        $this->student          =   $student                    =   auth()->user()->student;
        $student_section        =   $this->studentSectionAssignment();
        $this->student_section  =   $student_section;
        $this->online_class     =   $class                      =   $this->getOnlineClass($code);
        $my_classes             =   $this->getOnlineClasses();
        $section_ids            =   collect($student_section)->pluck('section_id')->toArray();

        if(!$this->online_class) {
            abort(403, 'Mismatch Class');
        }
        if(!in_array($this->online_class->section_id, $section_ids)){
            abort(403, 'Mismatch Class');
        }
        if(!$class->course)
        {
            abort(404, 'Class Course Not Found');
        }
        $module = OnlineClassModule::with('course','course.modules', 'topics')
                    ->where('online_course_id', $class->course->id)
                    ->where('id', $module_id)
                    ->first();

        if(!$module) {
            abort(403, 'Module Not Found.');
        }
        $modules = OnlineClassModule::where('online_course_id', $class->course->id)
                    ->get();
        $user    = auth()->user()->student;

        $enrollment         =   $student->enrollments->where('school_year_id', $class->school_year_id)
                                    ->where('term_type', $class->term_type)
                                    ->where('is_applicant', 0)
                                    ->first();

        $class_attendance   =   OnlineClassAttendance::getStudentAttendanceToday($class->id, $student->id);

        return view('student.online-class.show_module', compact(['modules', 'module', 'class', 'user', 'my_classes', 'class_attendance', 'enrollment']));
    }

    public function showModuleTopics($code, $module_id) {
        $this->student          =   $student                    =   auth()->user()->student;
        $student_section        =   $this->studentSectionAssignment();
        $this->student_section  =   $student_section;
        $this->online_class     =   $class                      =   $this->getOnlineClass($code);
        $my_classes             =   $this->getOnlineClasses();
        $section_ids            =   collect($student_section)->pluck('section_id')->toArray();


        if(!$this->online_class) {
            abort(403, 'Mismatch Class');
        }
        if(!in_array($this->online_class->section_id, $section_ids)){
            abort(403, 'Mismatch Class');
        }
        if(!$class->course)
        {
            abort(404, 'Class Course Not Found');
        }
        $module                 =   OnlineClassModule::with('course', 'course.modules', 'topics')
                                                    ->where('online_course_id', $class->course->id)
                                                    ->where('id', $module_id)
                                                    ->first();
        if(!$module) {
            abort(404, 'Module not Found');
        }
        $topics     =   $this->getModuleTopics($class->course->id, $module_id);
        $user       =   auth()->user()->student;

        $enrollment         =   $student->enrollments->where('school_year_id', $class->school_year_id)
                                    ->where('term_type', $class->term_type)
                                    ->where('is_applicant', 0)
                                    ->first();

        $class_attendance   =   OnlineClassAttendance::getStudentAttendanceToday($class->id, $student->id);

        return view('student.online-class.topics_list', compact(['module', 'topics', 'class', 'user', 'my_classes', 'class_attendance', 'enrollment']));
    }

    public function showTopic(Request $request, $code, $module_id, $topic_id){
        $this->student          =   $student                    =   auth()->user()->student;
        $student_section        =   $this->studentSectionAssignment();
        $this->student_section  =   $student_section;
        $this->online_class     =   $class                      =   $this->getOnlineClass($code);
        $my_classes             =   $this->getOnlineClasses();
        $section_ids            =   collect($student_section)->pluck('section_id')->toArray();
        $user                   =   auth()->user()->student;

        if(!$this->online_class) 
        {
            abort(403, 'Mismatch Class');
        }
        if(!in_array($this->online_class->section_id, $section_ids)){
            abort(403, 'Mismatch Class');
        }

        if(!$class->course)
        {
            abort(404, 'Class Course Not Found');
        }
        $module         =   OnlineClassModule::with('course', 'course.modules', 'topics')
                                ->where('online_course_id', $class->course->id)
                                ->where('id', $module_id)
                                ->first();
        if(!$module) {
            abort(404, 'Module not Found');
        }
        $topic          =   OnlineClassTopic::with('course', 'module', 'module.course')
                                ->where('online_class_module_id', $module_id)
                                ->where('online_course_id', $class->course->id)
                                ->where('id', $topic_id)
                                ->first();
        $topic_pages    =   OnlineTopicPage::where('online_class_topic_id', $topic_id)->paginate(1);

        $topics     =   $this->getModuleTopics($class->course->id, $module_id);

        // CHECK IF TOPICS IS FINISHED BY STUDENT
        if(count($topics)>0)
        {
            foreach ($topics as $key => $value) {
                $studentProgress    =   OnlineClassStudentProgress::where('online_class_topic_id', $value->id)
                                            ->where('student_id', auth()->user()->student->id)
                                            ->where('online_class_id', $class->id)
                                            ->first();
                if($studentProgress)
                {
                    $value['finished'] = 1;
                }
                else
                {
                    $value['finished'] = 0;
                }
            }
        }
        if(!$topic) {
            abort(404, 'Topic not Found');
        }

        $imageExtensions = array('jpg'=>'jpg', 'jpeg'=>'jpeg', 'gif'=>'gif', 'png'=>'png', 'bmp'=>'bmp', 'svg'=>'svg', 'svgz'=>'svgz', 'cgm'=>'cgm', 'djv'=>'djv', 'djvu'=>'djvu', 'ico'=>'ico', 'ief'=>'ief','jpe'=>'jpe', 'pbm'=>'pbm', 'pgm'=>'pgm', 'pnm'=>'pnm', 'ppm'=>'ppm', 'ras'=>'ras', 'rgb'=>'rgb', 'tif'=>'tif', 'tiff'=>'tiff', 'wbmp'=>'wbmp', 'xbm'=>'xbm', 'xpm'=>'xpm', 'xwd'=>'xwd');

        $enrollment         =   $student->enrollments->where('school_year_id', $class->school_year_id)
                                    ->where('term_type', $class->term_type)
                                    ->where('is_applicant', 0)
                                    ->first();

        $class_attendance   =   OnlineClassAttendance::getStudentAttendanceToday($class->id, $student->id);

        if($request->ajax())
        {
            $view = view('student.online-class.ajaxTopicPage', compact('module', 'topics', 'topic', 'topic_pages', 'class', 'my_classes', 'imageExtensions'))->render();
            return response()->json(['html'=>$view]); 
        }

        return view('student.online-class.topic_show', compact(['module', 'topics', 'topic', 'topic_pages', 'class', 'user', 'my_classes', 'class_attendance', 'enrollment', 'imageExtensions']));
    }

    public function showTopicPage($code, $module_id, $topic_id, $page_id){
        $this->student          =   $student                    =   auth()->user()->student;
        $student_section        =   $this->studentSectionAssignment();
        $this->student_section  =   $student_section;
        $this->online_class     =   $class                      =   $this->getOnlineClass($code);
        $my_classes             =   $this->getOnlineClasses();

        $selected_page          =   OnlineTopicPage::with('topic', 'topic.module', 'topic.module.course', 'topic.pages')
                                        ->where('id', $page_id)->first();
        $next_topic_page        =   OnlineTopicPage::where('online_class_topic_id', $topic_id)
                                        ->where('id', '>', $page_id)
                                        ->first();
        $prev_topic_page        =   OnlineTopicPage::where('online_class_topic_id', $topic_id)
                                        ->where('id', '<', $page_id)
                                        ->first();
        // VALIDATIONS
        $section_ids            =   collect($student_section)->pluck('section_id')->toArray();

        if(!$this->online_class) 
        {
            abort(403, 'Mismatch Class');
        }
        if(!in_array($this->online_class->section_id, $section_ids)){
            abort(403, 'Mismatch Class');
        }
        if(!$class->course)
        {
            abort(404, 'Class Course Not Found.');
        }
        if(!$selected_page) {
            abort(404, 'Topic Page Not Found.'); 
        }
        if(!$selected_page->topic) {
            abort(404, 'Class Topic Not Found.'); 
        }
        if(!$selected_page->topic->module)
        {
            abort(404, 'Class Module Not Found.'); 
        }
        if(!$selected_page->topic->module->course)
        {
            abort(404, 'Class Course Not Found.'); 
        }
        if($selected_page->topic->id != $topic_id || $selected_page->topic->module->id != $module_id)
        {
            abort(403, 'Mismatch Required Parameters.'); 
        }
        $user       =   auth()->user()->student;
        $imageExtensions = array('jpg'=>'jpg', 'jpeg'=>'jpeg', 'gif'=>'gif', 'png'=>'png', 'bmp'=>'bmp', 'svg'=>'svg', 'svgz'=>'svgz', 'cgm'=>'cgm', 'djv'=>'djv', 'djvu'=>'djvu', 'ico'=>'ico', 'ief'=>'ief','jpe'=>'jpe', 'pbm'=>'pbm', 'pgm'=>'pgm', 'pnm'=>'pnm', 'ppm'=>'ppm', 'ras'=>'ras', 'rgb'=>'rgb', 'tif'=>'tif', 'tiff'=>'tiff', 'wbmp'=>'wbmp', 'xbm'=>'xbm', 'xpm'=>'xpm', 'xwd'=>'xwd');

        $enrollment         =   $student->enrollments->where('school_year_id', $class->school_year_id)
                                    ->where('term_type', $class->term_type)
                                    ->where('is_applicant', 0)
                                    ->first();

        $class_attendance   =   OnlineClassAttendance::getStudentAttendanceToday($class->id, $student->id);

        return view('student.online-class.show_topic_page', compact(['selected_page', 'next_topic_page', 'prev_topic_page', 'class', 'user', 'my_classes', 'class_attendance', 'enrollment', 'imageExtensions']));
    }

    public function searchClasses(Request $request)
    {
        $this->student      =   $student = auth()->user()->student;
        $classes            =   null;
        $student_section    =   $this->studentSectionAssignment();
        $whereClause        = [];

        if(request()->term !== null) {
            $whereClause['term_type'] = request()->term;
        }

        if( $student_section ?? '')
        {
            $classes    =   OnlineClass::join('subject_managements', function ($join) use ($request) {
                                        $join->on('subject_managements.id', '=', 'online_classes.subject_id')
                                        ->where('subject_managements.subject_title', 'LIKE', '%' . $request->search . '%');
                                    })
                                    ->where($whereClause)
                                    ->where('school_year_id', SchoolYear::active()->first()->id)
                                    ->whereIn('section_id', collect($student_section)->pluck('section_id'))
                                    // ->whereIn('term_type', collect($student_section)->pluck('term_type'))
                                    // ->whereIn('summer', collect($student_section)->pluck('summer'))
                                    ->active()
                                    ->notArchive()
                                    ->paginate(8);
        }
        return response()->json(['classes' => $classes]);
    }

    public function getOnlineClasses(){
        $this->student      =   $student = auth()->user()->student;
        $classes            =   null;
        $student_section    =   $this->studentSectionAssignment();

        if( $student_section ?? '')
        {
            $classes    =   OnlineClass::with('teacher', 'course', 'course.modules', 'course.modules.topics')
                                    ->where('school_year_id', SchoolYear::active()->first()->id)
                                    ->whereIn('section_id', collect($student_section)->pluck('section_id'))
                                    // ->whereIn('term_type', collect($student_section)->pluck('term_type'))
                                    // ->whereIn('summer', collect($student_section)->pluck('summer'))
                                    ->active()
                                    ->notArchive()
                                    ->get();
                                }
        return $classes;
    }

    public function getVideoConferenceStatus(){
        $classes = $this->getOnlineClasses();
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

    public function studentSectionAssignment(){
        $this->student      =   $student = auth()->user()->student;
        $schoolYear         =   SchoolYear::active()->first();
        $student_section    =   [];

        if(!$this->student || !$schoolYear){
            return null;
        }

        if(!$this->student->studentnumber){
            return null;
        }

        // Get All Sections Of Active School Year
        $studentSectionAssignments  =   StudentSectionAssignment::where('school_year_id', $schoolYear->id)->get();

        if(!$studentSectionAssignments){
            return null;
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
             return null;
        }

        if(count($student_section) <= 0){
            return null;
        }

        return $student_section;
    }

    public function getOnlineClass($code){
        
        $class    =   OnlineClass::with('subject', 'section', 'teacher', 'schoolYear', 'course', 'course.modules', 'course.modules.topics')
                                    ->where('school_year_id', SchoolYear::active()->first()->id)
                                    ->where('code', $code)
                                    ->notArchive()
                                    ->active()
                                    ->first();
        // if($class) {
        //     $class->conference_status = OnlineClass::getConferenceStatus($class->code);
        // }
        return $class;
    }

    /* JOIN VIDEO CONFERENCE USING BBB */
    // public function joinConference($code) 
    // {
    //     $student = auth()->user()->student;
    //     $url = BBB::joinVideoConference($code,$student->fullname_last_first);
    //     return redirect()->to($url);
    // }
    
    /* JOIN VIDEO CONFERENCE USING ZOOM */
    public function joinConference($code) 
    {
        $student     =  auth()->user()->student;
        $school_year =  SchoolYear::active()->first();
        $my_classes  =  OnlineClass::getOnlineClasses($student, $school_year);
        $class       =  OnlineClass::where('school_year_id', $school_year->id)
                                    ->where('code', $code)
                                    ->notArchive()
                                    ->active()
                                    ->first();

        if(! $class) {
            \Alert::warning("Online Class Not Found.")->flash();
            return redirect()->to('student/online-class');
        }
        if(! in_array($class->id, $my_classes->pluck('id')->toArray())) {
            \Alert::warning("Unauthorized access.")->flash();
            return redirect()->to('student/online-class');
        }
        if(! $class->conference_status) {
            \Alert::warning("Online Class is not yet Ongoing.")->flash();
            return redirect()->to('student/online-post?class_code=' . $class->code);
        }
        if($class->join_url) {
            return redirect()->to($class->join_url);
        }
        
        \Alert::warning("Online Class Join URL Not Found.")->flash();
        return redirect()->to('student/online-post?class_code=' . $class->code);
    }

    public function getModuleTopics($course_id, $module_id){
        $topics = OnlineClassTopic::with('module', 'module.course', 'course')
                    ->where('online_course_id', $course_id)
                    ->where('online_class_module_id', $module_id)
                    ->get();
        return $topics;
    }

    // SAVING STUDENT PROGRESS
    public function submitProgress($code, $module_id, $topic_id)
    {
        $this->student          =   $student                    =   auth()->user()->student;
        $student_section        =   $this->studentSectionAssignment();
        $this->student_section  =   $student_section;
        $this->online_class     =   $class                      =   $this->getOnlineClass($code);
        $my_classes             =   $this->getOnlineClasses();

        if(!$this->online_class) 
        {
            abort(403, 'Mismatch Class');
        }
        if(!in_array($class->section_id, collect($student_section)->pluck('section_id')->toArray())){
            abort(403, 'Mismatch Class');
        }
        if(!in_array($class->term_type, collect($student_section)->pluck('term_type')->toArray())){
            abort(403, 'Mismatch Class');
        }
        if(!in_array($class->summer, collect($student_section)->pluck('summer')->toArray())){
            abort(403, 'Mismatch Class');
        }

        if(!$class->course)
        {
            abort(404, 'Class Course Not Found');
        }
        $module         =   OnlineClassModule::with('course', 'course.modules', 'topics')
                                ->where('online_course_id', $class->course->id)
                                ->where('id', $module_id)
                                ->first();
        if(!$module) {
            abort(404, 'Module not Found');
        }
        $topic          =   OnlineClassTopic::with('course', 'module', 'module.course')
                                ->where('online_class_module_id', $module_id)
                                ->where('online_course_id', $class->course->id)
                                ->where('id', $topic_id)
                                ->first();
        $next_topic     =   OnlineClassTopic::with('course', 'module', 'module.course')
                                ->where('online_class_module_id', $module_id)
                                ->where('online_course_id', $class->course->id)
                                ->where('id', '>',  $topic_id)
                                ->first();
        $prev_topic     =   OnlineClassTopic::with('course', 'module', 'module.course')
                                ->where('online_class_module_id', $module_id)
                                ->where('online_course_id', $class->course->id)
                                ->where('id', '<',  $topic_id)
                                ->orderBy('id', 'DESC')
                                ->first();
        $topic_pages    =   OnlineTopicPage::where('online_class_topic_id', $topic_id)->paginate(1);

        $topics     =   $this->getModuleTopics($class->course->id, $module_id);
        if(!$topic) {
            abort(404, 'Topic not Found');
        }

        // FOR SAVING PROGRESS OF STUDENT
        $studentProgress    =   OnlineClassStudentProgress::where('online_class_topic_id', $topic->id)
                                    ->where('student_id', auth()->user()->student->id)
                                    ->where('online_class_id', $class->id)
                                    ->first();
        if(!$studentProgress)
        {
            $student_progress = new OnlineClassStudentProgress();
            $student_progress->student_id           = auth()->user()->student->id;
            $student_progress->online_class_id      = $class->id;
            $student_progress->online_class_topic_id = $topic->id;
            $student_progress->save();

            if($next_topic)
            {
                return redirect()->to('student/online-class-topic/'.$class->code.'/'.$module->id.'/'.$next_topic->id);
            }
        }

        return redirect()->to('student/online-class/course/'.$class->code);
    }

    public function showStudentList($class_code)
    {
        $user                   =   auth()->user()->student;
        $this->student          =   $student                    =   auth()->user()->student;
        $student_section        =   $this->studentSectionAssignment();
        $this->student_section  =   $student_section;
        $this->online_class     =   $class                      =   $this->getOnlineClass($class_code);
        $quipperAccount         =   QuipperStudentAccount::where('student_id', auth()->user()->student->id)
                                                        ->first();

        // VALIDATIONS
        $section_ids            =   collect($student_section)->pluck('section_id')->toArray();
        if(!$class)
        {
            abort(403, 'Mismatch Class');
        }
        if(!in_array($class->section_id, $section_ids)){
            abort(403, 'Mismatch Class');
        }
        if(!in_array($class->term_type, collect($student_section)->pluck('term_type')->toArray())){
            abort(403, 'Mismatch Class');
        }
        if(!in_array($class->summer, collect($student_section)->pluck('summer')->toArray())){
            abort(403, 'Mismatch Class');
        }
        if($class->activeStudentSectionAssignment)
        {
            if(count(json_decode($class->activeStudentSectionAssignment->students))>0)
            {
                $student_list   =   Student::whereIn('studentnumber', json_decode($class->activeStudentSectionAssignment->students))
                                        ->orderBy('gender', 'ASC')
                                        ->orderBy('lastname', 'ASC')
                                        ->orderBy('firstname', 'ASC')
                                        ->orderBy('middlename', 'ASC')
                                        ->select('id', 'lastname', 'firstname', 'middlename', 'gender', 'photo')
                                        ->get();
            }
            else
            {
                $student_list = null;
            }
        }
        else
        {
            $student_list = null;
        }
        // dd($student_list);
        $enrollment         =   $student->enrollments->where('school_year_id', $class->school_year_id)
                                    ->where('term_type', $class->term_type)
                                    ->where('is_applicant', 0)
                                    ->first();

        $class_attendance   =   OnlineClassAttendance::getStudentAttendanceToday($class->id, $student->id);

        return view('student.online-class.class-student-list', compact(['student_list', 'class', 'user', 'quipperAccount', 'enrollment', 'class_attendance']));
    }

    /*
    |--------------------------------------------------------------------------
    | BIG BLUE BUTTON RECORDINGS
    |--------------------------------------------------------------------------
    */
    // public function showClassRecordings($class_code)
    // {
    //     $user                   =   auth()->user()->student;
    //     $this->student          =   $student                    =   auth()->user()->student;
    //     $student_section        =   $this->studentSectionAssignment();
    //     $this->student_section  =   $student_section;
    //     $this->online_class     =   $class                      =   $this->getOnlineClass($class_code);
    //     $quipperAccount         =   QuipperStudentAccount::where('student_id', auth()->user()->student->id)
    //                                                     ->first();
    //     // VALIDATIONS
    //     $section_ids            =   collect($student_section)->pluck('section_id')->toArray();
    //     if(!$class)
    //     {
    //         abort(403, 'Mismatch Class');
    //     }
    //     if(!in_array($class->section_id, $section_ids)){
    //         abort(403, 'Mismatch Class');
    //     }
    //     if(!in_array($class->term_type, collect($student_section)->pluck('term_type')->toArray())){
    //         abort(403, 'Mismatch Class');
    //     }
    //     if(!in_array($class->summer, collect($student_section)->pluck('summer')->toArray())){
    //         abort(403, 'Mismatch Class');
    //     }

    //     $enrollment         =   $student->enrollments->where('school_year_id', $class->school_year_id)
    //                                 ->where('term_type', $class->term_type)
    //                                 ->where('is_applicant', 0)
    //                                 ->first();

    //     $class_attendance   =   OnlineClassAttendance::getStudentAttendanceToday($class->id, $student->id);

    //     $recordingParams = new GetRecordingsParameters($class_code);
    //     $recordingParams->setMeetingId($class_code);

    //     $bbb = new BigBlueButton();
    //     $response = $bbb->getRecordings($recordingParams);

    //     $recordings = $response->getRawXml()->recordings->recording; 

    //     if ($response->getReturnCode() == 'SUCCESS') {
    //         return view('student.online-class.class-recordings', compact(['class', 'user', 'quipperAccount', 'recordings', 'class_attendance', 'enrollment']));
    //     }
    //     else {
    //         \Alert::error("Error, Something went wrong.")->flash();
    //         return redirect()->back();
    //     }
    // }
    
    /*
    |--------------------------------------------------------------------------
    | ZOOM RECORDINGS
    |--------------------------------------------------------------------------
    */
    public function showClassRecordings($class_code)
    {
        $user                   =   auth()->user()->student;
        $this->student          =   $student                    =   auth()->user()->student;
        $student_section        =   $this->studentSectionAssignment();
        $this->student_section  =   $student_section;
        $this->online_class     =   $class                      =   $this->getOnlineClass($class_code);
        $quipperAccount         =   QuipperStudentAccount::where('student_id', auth()->user()->student->id)
                                                        ->first();
        // VALIDATIONS
        $section_ids            =   collect($student_section)->pluck('section_id')->toArray();
        if(!$class)
        {
            abort(403, 'Mismatch Class');
        }
        if(!in_array($class->section_id, $section_ids)){
            abort(403, 'Mismatch Class');
        }
        if(!in_array($class->term_type, collect($student_section)->pluck('term_type')->toArray())){
            abort(403, 'Mismatch Class');
        }
        if(!in_array($class->summer, collect($student_section)->pluck('summer')->toArray())){
            abort(403, 'Mismatch Class');
        }

        $enrollment         =   $student->enrollments->where('school_year_id', $class->school_year_id)
                                    ->where('term_type', $class->term_type)
                                    ->where('is_applicant', 0)
                                    ->first();

        $class_attendance   =   OnlineClassAttendance::getStudentAttendanceToday($class->id, $student->id);

        $recordings = $class->zoomRecordings; 

        return view('student.online-class.zoom_recordings', compact(['class', 'user', 'quipperAccount', 'recordings', 'class_attendance', 'enrollment']));
    }

    public function submitClassAttendance($qrcode)
    {
        abort_if(!request()->class_code, 404, 'Class Code Not Found.');
        abort_if(!request()->enrollment_id, 404, 'Enrollment ID Not Found.');

        $this->validateStudentSection(request()->class_code);

        $currentDate     =  Carbon::now()->toDateString();
        $currentTime     =  Carbon::now()->toTimeString();
        $currentDateTime =  Carbon::now();

        $enrollment     =   $this->student->enrollments->where('id', request()->enrollment_id)->first();
        $online_class   =   $this->online_class;

        abort_if(!$enrollment, 404, 'Enrollment Not Found.');
        abort_if(!$enrollment->qr_code, 404, 'Enrollment QR Code Not Found.');

        $userAttendance     =   OnlineClassAttendance::where('user_id', $this->student->id)
                                    ->where('user_type', 'App\Models\Student')
                                    ->where('created_at', '>=', $currentDate)
                                    ->where('created_at', '<=', $currentDate . ' 23:59:59')
                                    ->where('online_class_id', $online_class->id)
                                    ->first();

        if($userAttendance) {
            $userAttendance->time_out = $currentDateTime;
            $userAttendance->update();
            
            \Alert::success("Successfully Tap Out.")->flash();
            return redirect()->to('student/online-post?class_code='.$online_class->code);
        }

        $userAttendance     =   OnlineClassAttendance::create([
                                    'user_id'   => $this->student->id,
                                    'user_type' => 'App\Models\Student',
                                    'online_class_id' => $online_class->id,
                                    'time_in'   => $currentDateTime,
                                ]);

        \Alert::success("Successfully Tap In.")->flash();
        return redirect()->to('student/online-post?class_code='.$online_class->code);
    }

    public function tapClassAttendance($class_code)
    {
        abort_if(!request()->class_code, 404, 'Class Code Not Found.');

        $this->validateStudentSection($class_code);

        $currentDate     =  Carbon::now()->toDateString();
        $currentTime     =  Carbon::now()->toTimeString();
        $currentDateTime =  Carbon::now();

        $online_class   =   $this->online_class;

        $userAttendance     =   OnlineClassAttendance::where('user_id', $this->student->id)
                                    ->where('user_type', 'App\Models\Student')
                                    ->where('created_at', '>=', $currentDate)
                                    ->where('created_at', '<=', $currentDate . ' 23:59:59')
                                    ->where('online_class_id', $online_class->id)
                                    ->first();

        if($userAttendance) {
            $userAttendance->time_out = $currentDateTime;
            $userAttendance->update();
            
            \Alert::success("Successfully Tap Out.")->flash();
            return redirect()->to('student/online-post?class_code='.$online_class->code);
        }

        $userAttendance     =   OnlineClassAttendance::create([
                                    'user_id'   => $this->student->id,
                                    'user_type' => 'App\Models\Student',
                                    'online_class_id' => $online_class->id,
                                    'time_in'   => $currentDateTime,
                                ]);

        \Alert::success("Successfully Tap In.")->flash();
        return redirect()->back();
    }

    public function validateStudentSection($class_code) 
    {
        $this->student          =   $student                    =   auth()->user()->student;
        $student_section        =   $this->studentSectionAssignment();
        $this->student_section  =   $student_section;
        $this->online_class     =   $class                      =   $this->getOnlineClass($class_code);
        $quipperAccount         =   QuipperStudentAccount::where('student_id', auth()->user()->student->id)
                                                        ->first();

        // VALIDATIONS
        $section_ids            =   collect($student_section)->pluck('section_id')->toArray();
        if(!$class)
        {
            abort(403, 'Mismatch Class');
        }
        if(!in_array($class->section_id, $section_ids)){
            abort(403, 'Mismatch Class');
        }
        if(!in_array($class->term_type, collect($student_section)->pluck('term_type')->toArray())){
            abort(403, 'Mismatch Class');
        }
        if(!in_array($class->summer, collect($student_section)->pluck('summer')->toArray())){
            abort(403, 'Mismatch Class');
        }
    }
}
