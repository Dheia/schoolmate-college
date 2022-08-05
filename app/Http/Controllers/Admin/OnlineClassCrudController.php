<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\OnlineClassRequest as StoreRequest;
use App\Http\Requests\OnlineClassRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

// MODELS
use App\Models\OnlineClass;
use App\Models\OnlineClassAttendance;
use App\Models\User;
use App\Models\Student;
use App\Models\Employee;
use App\Models\SchoolYear;
use App\Models\TermManagement;
use App\Models\YearManagement;
use App\Models\SubjectMapping;
use App\Models\TeacherSubject;
use App\Models\TrackManagement;
use App\Models\SectionManagement;
use App\Models\SubjectManagement;
use App\Models\StudentSectionAssignment;
use App\Models\OnlineCourse;
use App\Models\OnlineClassModule;
use App\Models\OnlineClassTopic;
use App\Models\OnlineTopicPage;
use App\Models\OnlineClassQuiz;

use Symfony\Component\HttpFoundation\Request;

use File;
use Carbon\Carbon;
use App\Http\Controllers\BBB;

use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\GetRecordingsParameters;

/* ZOOM */
use App\Http\Controllers\ZoomMeetingController;
use App\Models\ZoomMeeting;
use App\Models\ZoomUser;

/**
 * Class OnlineClassCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class OnlineClassCrudController extends CrudController
{
    public $teacher_subjects    = null;
    public $teacher_subjects_id = null;
    public $teacher_sections_id = null;
    public $teacher_sections    = null;
    public $teacher_levels      = null;

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\OnlineClass');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/teacher-online-class');
        $this->crud->setEntityNameStrings('Online Class', 'Online Classes');
        /*
        |--------------------------------------------------------------------------
        | Get The Teacher Information (teacher_id: required)
        |--------------------------------------------------------------------------
        */
        if(!backpack_auth()->user()->employee) {
            // \Alert::warning("Missing Required Parameters!")->flash();
            // abort(403);
             abort(403, 'Your User Account Is Not Yet Tag As Employee');
        } else {
            
            if(backpack_auth()->user()->employee_id === null) {
                abort(403, 'Your User Account Is Not Yet Tag As Employee');
            }

            $this->data['teacher'] = User::where('employee_id', backpack_auth()->user()->employee_id)->first();

            if($this->data['teacher'] === null) {
                  abort(403, 'User Not Found');
            }
        }
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        // add asterisk for fields that are required in OnlineClassRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->setListView('onlineClass.myClasses.dashboard');
        $this->crud->setCreateView('onlineClass.myClasses.dashboard');
        $this->crud->setEditView('onlineClass.myClasses.edit');

        $this->data['user'] = backpack_user();

        if(request()->has('class_code')){
            
            $this->data['class']    =   $class  = self::getClass(request()->get('class_code'));

            if(!$class)
            {
                abort(403, 'Mismatch Class');
            }
            $this->data['course_tag'] = OnlineCourse::with('teacher', 'share')
                ->join('online_course_teacher', function ($join) {
                    $join->on('online_course_teacher.online_course_id', '=', 'online_courses.id')
                         ->where('online_course_teacher.teacher_id', '=',  $this->data['class']->teacher_id);
                })
                ->notArchive()
                ->active()
                ->get();

            $this->data['class_attendance'] =   OnlineClassAttendance::getEmployeeAttendanceToday($class->id, backpack_user()->employee_id);
        }

        $this->data['my_classes']   =   self::getMyClasses();
        // if(isset($class)){
        //     if($class)
        //     {
        //         if($class->teacher_id != backpack_auth()->user()->employee_id && !backpack_auth()->user()->hasRole('School Head'))
        //         {
        //            abort(403, 'Mismatch Class'); 
        //         }
        //     }
        // }   

        // IF Online Class ID IS IN URL
        // EDIT MY CLASS OR ASSIGN COURSE
        $online_class_id = \Route::current()->parameter('teacher_online_class');
        if($online_class_id)
        {
            if(!isset($class))
            {
                abort(403, 'Mismatch Class');
            }
            else if($online_class_id != $class->id)
            {
                abort(403, 'Mismatch Class');
            }

            if(!backpack_auth()->user()->hasRole('Administrator'))
            {
                if($class->teacher_id != backpack_auth()->user()->employee_id){
                    if(count($class->substitute_teachers)>0){
                        if(!in_array(backpack_auth()->user()->employee_id, $class->substitute_teachers)){
                            abort(403, 'Unauthorized access.');
                        }
                    }
                    else{
                        abort(403, 'Unauthorized access.');
                    }
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | FIELDS
        |--------------------------------------------------------------------------
        */
        $this->crud->addField([  // Select
            'name'      => 'online_course_id',
            'label'     => "Course",
            'type'      => 'select2', // the db column for the foreign key
            'entity'    => 'course', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model'     => "App\Models\OnlineCourse", // foreign key model
            'options'   => (function ($query) {
                return $query->where('teacher_id', $this->data['class']->teacher_id)->orWhereIn('id',  $this->data['course_tag']->pluck('online_course_id'))->get();
            }),
            'allows_null' => false,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ]

        ], 'update');

        $this->crud->addField([
            'name'  =>  'days',
            'label' =>  'Days',
            'type'  =>  'select2_from_array',
            'options'   =>  [
                'Monday'    => 'Monday', 
                'Tuesday'   => 'Tuesday', 
                'Wednesday' => 'Wednesday',
                'Thursday'  => 'Thursday',
                'Friday'    => 'Friday',
                'Saturday'  => 'Saturday'
            ],
            'allows_null' => false,
            'allows_multiple' => true
        ], 'update');

        $this->crud->addField([   // Time
            'name' => 'start_time',
            'label' => 'Start Time',
            'type' => 'time',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
        ]);

        $this->crud->addField([   // Time
            'name' => 'end_time',
            'label' => 'End Time',
            'type' => 'time',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
        ]);

        $this->crud->addField([
            'name'  =>  'substitute_teachers',
            'label' =>  'Substitute Teachers',
            'type'  =>  'select2_from_array',
            'options'   =>  Employee::get()->pluck('full_name', 'id'),
            'allows_null' => true,
            'allows_multiple' => true
        ], 'update');

        $this->crud->addField([   // color_picker
            'label' => 'Background Color',
            'name' => 'color',
            'type' => 'color_picker',
            'color_picker_options' => [
                'customClass' => 'custom-class'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
            'default' => '#3c8dbc'
        ]);

        $this->crud->addField([   // Checkbox
            'name' => 'link_to_quipper',
            'label' => 'Link to Quipper',
            'type' => 'checkbox'
        ], 'update');
    }

    public function store(StoreRequest $request)
    {
        $sy = SchoolYear::active()->first();
        if($sy == null) {
            \Alert::warning("No School Year Active")->flash();
            return redirect()->back();   
        }
        if(!$request->term_type)
        {
            $request->request->set('term_type', 'Full');
        }
        if(!$request->color)
        {
            $request->request->set('color', $request->color);
        }
        $request->request->set('school_year_id', $sy->id);
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        // if (!File::exists(public_path('uploads').'/Online Class/'.$request->code)) {
        //     File::makeDirectory(public_path('uploads').'/Online Class/'.$request->code,0775);
        // }
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        if(!$request->term_type)
        {
            $request->request->set('term_type', 'Full');
        }
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
         return redirect('admin/teacher-online-post?class_code='.$this->data['entry']->code);
    }

    public function showStudentList($class_code)
    {
        $user = backpack_user();

        $my_classes = self::getMyClasses();
        $class      = self::getClass($class_code);

        if(!$class)
        {
            abort(403, 'Mismatch Class');
        }
        if($class->teacher_id != backpack_auth()->user()->employee_id && !backpack_user()->hasRole('School Head'))
        {
            if($class->substitute_teachers){
                if(count($class->substitute_teachers)>0){
                    if(!in_array(backpack_auth()->user()->employee_id, $class->substitute_teachers)){
                        abort(403, 'Unauthorized access.');
                    } else {
                        $class->isTeacherSubstitute = 1;
                    }
                } else{
                    abort(403, 'Unauthorized access.');
                }
            } else {
                abort(403, 'Unauthorized access.');
            }
        }

        $class->activeStudentSectionAssignment =    $class->studentSectionAssignments->where('school_year_id', $class->school_year_id)
                                                        ->where('term_type', $class->term_type)
                                                        ->where('summer', $class->summer)
                                                        ->first();

        if($class->activeStudentSectionAssignment)
        {
            if(count(json_decode($class->activeStudentSectionAssignment->students))>0)
            {
                $student_list   =   Student::whereIn('studentnumber', json_decode($class->activeStudentSectionAssignment->students))
                                        ->orderBy('gender', 'ASC')
                                        ->orderBy('lastname', 'ASC')
                                        ->orderBy('firstname', 'ASC')
                                        ->orderBy('middlename', 'ASC')
                                        ->select('id', 'studentnumber', 'lastname', 'firstname', 'middlename', 'gender', 'photo')
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
        
        // Check If User is Substitute Teacher Of Class and Video Status
        if($class){
            $class->isTeacherSubstitute   = 0;
           
            if($class->substitute_teachers){
                if(count($class->substitute_teachers)>0){
                    if(in_array(backpack_auth()->user()->employee_id, $class->substitute_teachers)){
                        $class->isTeacherSubstitute = 1;
                    }
                }
            }
        }

        $class_attendance =   OnlineClassAttendance::getEmployeeAttendanceToday($class->id, backpack_user()->employee_id);

        return view('onlineClass.myClasses.class_student_list', compact(['student_list', 'my_classes', 'class', 'user', 'class_attendance']))
                ->with('crud', $this->crud);
    }

    public function showClassAttendance($class_code)
    {
        $user = backpack_user();

        $my_classes = $this->getMyClasses();
        $class      = $this->getClass($class_code);

        if(!$class)
        {
            abort(403, 'Mismatch Class');
        }
        if($class->teacher_id != backpack_auth()->user()->employee_id && !backpack_user()->hasRole('School Head'))
        {
            if($class->substitute_teachers){
                if(count($class->substitute_teachers)>0){
                    if(!in_array(backpack_auth()->user()->employee_id, $class->substitute_teachers)){
                        abort(403, 'Unauthorized access.');
                    } else {
                        $class->isTeacherSubstitute = 1;
                    }
                } else{
                    abort(403, 'Unauthorized access.');
                }
            } else {
                abort(403, 'Unauthorized access.');
            }
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

        // Check If User is Substitute Teacher Of Class and Video Status
        if($class){
            $class->isTeacherSubstitute   = 0;
           
            if($class->substitute_teachers){
                if(count($class->substitute_teachers)>0){
                    if(in_array(backpack_auth()->user()->employee_id, $class->substitute_teachers)){
                        $class->isTeacherSubstitute = 1;
                    }
                }
            }
        }

        $class_attendance =   OnlineClassAttendance::getEmployeeAttendanceToday($class->id, backpack_user()->employee_id);

        return view('onlineClass.myClasses.class_attendance', compact(['student_list', 'my_classes', 'class', 'user', 'class_attendance']))
                ->with('crud', $this->crud);
    }

    public function showClassCourse($class_code)
    {
        $user       = backpack_user();
        $class      = self::getClass($class_code);

        if(!$class)
        {
            abort(403, 'Mismatch Class');
        }
        // Get Class Course
        if(!$class->online_course_id)
        {
            abort(403, 'No Course Assigned');
        }
        $class_course = $this->getClassCourse($class->online_course_id);

        if(!$class_course)
        {
            abort(404, 'Course Not Found');
        }

        $class_attendance =   OnlineClassAttendance::getEmployeeAttendanceToday($class->id, backpack_user()->employee_id);

        return view('onlineClass.myClasses.class_course', compact(['class', 'user', 'class_attendance']))->with('selected_course', $class_course);
    }

    public function showCourseTopic(Request $request, $class_code, $module_id, $topic_id)
    {
        $user       = backpack_user();
        $class      = $this->getClass($class_code);
        if(!$class)
        {
            abort(403, 'Mismatch Class');
        }
        // Get Class Course
        if(!$class->online_course_id)
        {
            abort(403, 'No Course Assigned');
        }
        $class_course = $this->getClassCourse($class->online_course_id);

        if(!$class_course)
        {
            abort(404, 'Course Not Found');
        }

        $selected_topic     =   OnlineClassTopic::with('module')
                                    ->where('online_class_module_id', $module_id)
                                    ->where('online_course_id', $class_course->id)
                                    ->where('id', $topic_id)
                                    ->first();

        $next_topic         =   OnlineClassTopic::where('online_class_module_id', $module_id)
                                    ->where('online_course_id', $class_course->id)
                                    ->where('id', '>', $topic_id)
                                    ->first();

        $prev_topic         =   OnlineClassTopic::where('online_class_module_id', $module_id)
                                    ->where('online_course_id', $class_course->id)
                                    ->where('id', '<', $topic_id)
                                    ->first();

        $topics             =   OnlineClassTopic::with('module', 'module.course', 'module.topics')
                                    ->where('online_class_module_id', $module_id)
                                    ->where('online_course_id', $class_course->id)
                                    ->get();
        if(!$selected_topic)
        {
            abort(404, 'Topic Not Found');
        }

        $selected_topic_pages = OnlineTopicPage::where('online_class_topic_id', $topic_id)->paginate(1);

        if($request->ajax())
        {
            $view = view('onlineClass.myClasses.ajaxTopicPage',compact('selected_topic', 'selected_topic_pages'))->render();
            return response()->json(['html'=>$view]); 
        }

        $my_classes = $this->getMyClasses();
        $imageExtensions = ['jpg'=>'jpg', 'jpeg'=>'jpeg', 'gif'=>'gif', 'png'=>'png', 'bmp'=>'bmp', 'svg'=>'svg', 'svgz'=>'svgz', 'cgm'=>'cgm', 'djv'=>'djv', 'djvu'=>'djvu', 'ico'=>'ico', 'ief'=>'ief','jpe'=>'jpe', 'pbm'=>'pbm', 'pgm'=>'pgm', 'pnm'=>'pnm', 'ppm'=>'ppm', 'ras'=>'ras', 'rgb'=>'rgb', 'tif'=>'tif', 'tiff'=>'tiff', 'wbmp'=>'wbmp', 'xbm'=>'xbm', 'xpm'=>'xpm', 'xwd'=>'xwd'];

        $title = "Class Topics";

        $class_attendance =   OnlineClassAttendance::getEmployeeAttendanceToday($class->id, backpack_user()->employee_id);

        return view('onlineClass.myClasses.show_topic', compact(['class_attendance', 'user', 'selected_topic', 'selected_topic_pages', 'topics', 'next_topic', 'prev_topic', 'class', 'my_classes', 'imageExtensions', 'title']))->with('course', $class_course);
    }

    public function ajax ()
    {
        if($request->ajax())
        {
            $view = view('onlineClass.myClasses.ajaxTopicPage',compact('selected_topic', 'selected_topic_pages'))->render();
            return response()->json(['html'=>$view]); 
        }
    }

    public function showCourseModule($class_code, $module_id)
    {
        $user       = backpack_user();
        $class      = $this->getClass($class_code);
        if(!$class)
        {
            abort(403, 'Mismatch Class');
        }
        // Get Class Course
        if(!$class->online_course_id)
        {
            abort(403, 'No Course Assigned');
        }
        $class_course = $this->getClassCourse($class->online_course_id);

        if(!$class_course)
        {
            abort(404, 'Course Not Found');
        }

        $module             =   OnlineClassModule::where('id', $module_id)->where('online_course_id', $class_course->id)
                                    ->first();
        if(!$module)
        {
            abort(404, 'Module Not Found');
        }

        $topics             =   OnlineClassTopic::with('module', 'module.course', 'module.topics')
                                    ->where('online_class_module_id', $module->id)
                                    ->where('online_course_id', $class_course->id)
                                    ->get();
        $crud = $this->crud;
        $title = "Class Topics";

        $class_attendance =   OnlineClassAttendance::getEmployeeAttendanceToday($class->id, backpack_user()->employee_id);

        return view('onlineClass.myClasses.show_module', compact(['class_attendance', 'user', 'module', 'topics', 'class', 'crud', 'title']))->with('course', $class_course);
    }

    public function showTopicPage($class_code, $module_id, $topic_id, $page_id)
    {
        $user       = backpack_user();
        $class      = $this->getClass($class_code);
        if(!$class)
        {
            abort(403, 'Mismatch Class');
        }
        // Get Class Course
        if(!$class->online_course_id)
        {
            abort(403, 'No Course Assigned');
        }
        $class_course = $this->getClassCourse($class->online_course_id);

        if(!$class_course)
        {
            abort(404, 'Course Not Found');
        }
        $pages              =   OnlineTopicPage::where('online_class_topic_id', $topic_id)->get();
        $selected_page      =   OnlineTopicPage::with('topic', 'topic.module', 'topic.module.course')
                                    ->where('online_class_topic_id', $topic_id)
                                    ->where('id', $page_id)
                                    ->first();
        if(!$selected_page)
        {
            abort(404, 'Topic Page Not Found');
        }
        if(!$selected_page->topic->module)
        {
            abort(404, 'Topic Page Not Found');
        }
        if(!$selected_page->topic->module->course)
        {
            abort(404, 'Topic Page Not Found');
        }
        if($selected_page->topic->module->id != $module_id || $selected_page->topic->module->course->id != $class_course->id)
        {
            abort(404, 'Topic Page Not Found');
        }

        $next_topic_page    =   OnlineTopicPage::where('online_class_topic_id', $topic_id)
                                    ->where('id', '>', $selected_page->id)
                                    ->first();

        $prev_topic_page    =   OnlineTopicPage::where('online_class_topic_id', $topic_id)
                                    ->where('id', '<', $selected_page->id)
                                    ->first();

        $my_classes = $this->getMyClasses();
        $imageExtensions = ['jpg'=>'jpg', 'jpeg'=>'jpeg', 'gif'=>'gif', 'png'=>'png', 'bmp'=>'bmp', 'svg'=>'svg', 'svgz'=>'svgz', 'cgm'=>'cgm', 'djv'=>'djv', 'djvu'=>'djvu', 'ico'=>'ico', 'ief'=>'ief','jpe'=>'jpe', 'pbm'=>'pbm', 'pgm'=>'pgm', 'pnm'=>'pnm', 'ppm'=>'ppm', 'ras'=>'ras', 'rgb'=>'rgb', 'tif'=>'tif', 'tiff'=>'tiff', 'wbmp'=>'wbmp', 'xbm'=>'xbm', 'xpm'=>'xpm', 'xwd'=>'xwd'];

        $class_attendance =   OnlineClassAttendance::getEmployeeAttendanceToday($class->id, backpack_user()->employee_id);

        return  view('onlineClass.myClasses.show_topic_page', compact(['class_attendance', 'user', 'selected_page', 'next_topic_page', 'prev_topic_page', 'class', 'my_classes', 'imageExtensions', 'pages']))->with('course', $class_course);

    }

    /* START VIDEO CONFERENCE USING BBB */
    // public function videoConference(Request $request)
    // {
    //     $code    = $request->class_code;
    //     $class   = self::getClass($code);
    //     $meetingId = $request->class_code;
    //     return BBB::createMeeting($meetingId, $class);
    // }
    
    /* JOIN VIDEO CONFERENCE USING BBB */
    // public function joinConference($code)
    // {
    //     $employee = backpack_auth()->user()->employee;
    //     $url = BBB::joinVideoConference($code,$employee->full_name);
    //     return redirect()->to($url);
    // }

    /**
     * START VIDEO CONFERENCE USING ZOOM 
     */
    public function videoConference(Request $request)
    {
        $employee   = backpack_auth()->user()->employee;
        $code       = $request->class_code;
        $class      = self::getClass($code);
        $my_classes = self::getUserAllClasses();

        if(! $class) {
            \Alert::warning("Online Class Not Found.")->flash();
            return redirect()->to('admin/teacher-online-class');
        }
        if(! $my_classes) {
            \Alert::warning("Online Class Not Found.")->flash();
            return redirect()->to('admin/teacher-online-class');
        }
        if(! in_array($class->id, $my_classes->pluck('id')->toArray())) {
            \Alert::warning("Unauthorized access.")->flash();
            return redirect()->to('admin/teacher-online-class');
        }

        $zoom_user = ZoomUser::where('active', '<', 2)->inRandomOrder()->first();

        if(! $zoom_user) {
            \Alert::warning("You have reached the maximum number of active host. Please contact your accounts manager.")->flash();
            return redirect('admin/teacher-online-class');
        }

        /************************
        ** Create Zoom Meeting **
        ************************/
        $meeting_data = [
            'code'      => $class->code,
            'topic'     => $class->subject_name,
            'agenda'    => $class->description,
            'password'  => $class->code,
            'host_email' => $zoom_user ? $zoom_user->email : null,
            'firstname' => $employee->firstname,
            'lastname' => $employee->lastname
        ];

        $zoomController  = new ZoomMeetingController();
        $create_meeting = $zoomController->createMeeting($meeting_data);

        if(! $create_meeting['success']) {
            \Alert::warning("Unable to create meeting.")->flash();
            return redirect('admin/teacher-online-class');
        }

        /***********************
        ** Store Zoom Meeting **
        ***********************/
        $zoom_meeting = ZoomMeeting::create([
            'meetingable_id'    => $class->id,
            'meetingable_type'  => 'App\Models\OnlineClass',
            'zoom_user_id'      => $zoom_user->id,
            'employee_id'       => $employee->id,
            'zoom_uuid'         => $create_meeting['data']['uuid'],
            'zoom_id'           => $create_meeting['data']['id'],
            'zoom_host_id'      => $create_meeting['data']['host_id'],
            'data'   => json_encode($create_meeting['data']),
            'status' => $create_meeting['data']['status'],
            'active' => 1
        ]);

        /*******************
        ** UPDATE MEETING **
        *******************/
        $meeting   = $zoom_meeting->meetingable;
        $meeting->zoom_id = $create_meeting['data']['id'];
        $meeting->status  = $create_meeting['data']['status'];
        $meeting->save();

        /*************************
        ** Set Zoom User Active **
        *************************/
        // $zoom_user->update([
        //     'active' => 1
        // ]);

        return redirect()->away($create_meeting['data']['start_url']);
    }
    
    /* JOIN VIDEO CONFERENCE USING ZOOM */
    public function joinConference($code)
    {
        $employee   = backpack_auth()->user()->employee;
        $class      = self::getClass($code);
        $my_classes = self::getUserAllClasses();

        if(! $class) {
            \Alert::warning("Online Class Not Found.")->flash();
            return redirect()->to('admin/teacher-online-class');
        }
        if(! $my_classes) {
            \Alert::warning("Online Class Not Found.")->flash();
            return redirect()->to('admin/teacher-online-class');
        }
        if(! in_array($class->id, $my_classes->pluck('id')->toArray())) {
            \Alert::warning("Unauthorized access.")->flash();
            return redirect()->to('admin/teacher-online-class');
        }
        if(! $class->conference_status) {
            \Alert::warning("Online Class is not yet Ongoing.")->flash();
            return redirect()->to('/admin/teacher-online-post?class_code=' . $class->code);
        }
        if($class->join_url) {
            return redirect()->to($class->join_url);
        }

        \Alert::warning("Online Class Join URL Not Found.")->flash();
        return redirect()->to('admin/teacher-online-post?class_code=' . $class->code);
    }

    public function getMyClasses()
    {
        $employee_id = backpack_auth()->user()->employee_id;
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
                                ->paginate(4);
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
                                ->orWhere('substitute_teachers', 'like', "%\"{$employee_id}\"%")
                                ->activeSchoolYear()
                                ->notArchive()
                                ->active()
                                ->paginate(4);
            return $classes;
        }
        return null;
    }

    public function getUserAllClasses()
    {
        $employee_id = backpack_auth()->user()->employee_id;
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
                                ->orWhere('substitute_teachers', 'like', "%\"{$employee_id}\"%")
                                ->activeSchoolYear()
                                ->notArchive()
                                ->active()
                                ->get();
            return $classes;
        }
        return collect([]);
    }

    public function getClass($class_code)
    {
        $employee_id = backpack_auth()->user()->employee_id;
        if(backpack_auth()->user()->hasRole('School Head')){

            $class  =   OnlineClass::with([
                            'teacher', 
                            'subject',
                            'activeStudentSectionAssignment',
                            'course'
                        ])
                        ->where('code', $class_code)
                        ->activeSchoolYear()
                        ->notArchive()
                        ->active()
                        ->first();
            return $class;
        }
        else
        {
            $class  =   OnlineClass::with([
                            'teacher', 
                            'subject',
                            'activeStudentSectionAssignment'
                        ])
                        ->where('teacher_id', backpack_auth()->user()->employee_id)
                        ->orWhere('substitute_teachers', 'like', "%\"{$employee_id}\"%")
                        ->activeSchoolYear()
                        ->notArchive()
                        ->active()
                        ->get();
            $class  =   $class->where('code', request()->class_code)->first();
            return $class;
        }
        return null;
    }

    public function setArchive ($id)
    {
        $class = $this->crud->model::where('id', $id)->first();
        if($class)
        {
            $class->archive = 1;
            if($class->update())
            {
                \Alert::success('Item Successfully Set to Archive.')->flash();
                return \Redirect::to($this->crud->route.'?teacher_id='.request()->teacher_id);
            }
            else
            {
                \Alert::error('Error, Something Went Wrong, Please Try Again.')->flash();
                return \Redirect::to($this->crud->route.'?teacher_id='.request()->teacher_id);
            }
        }
    }

    public function getTracks ()
    {
        $tracks = TrackManagement::where('level_id', request()->level_id)->whereIn('id', collect($this->teacher_sections)->pluck('track_id'))->get();
        return $tracks;
    }

    public function getSections ()
    {
        $sections = SectionManagement::where([ 
                                                'level_id' => request()->level_id, 
                                                'track_id' => request()->track_id 
                                        ])
                                        ->whereIn('id', collect($this->teacher_sections)->pluck('id'))
                                        ->get();
        return $sections ?? [];
    }

    public function getSubjects ()
    {
        $section = SectionManagement::where('id', request()->section_id)->first();

        if($section == null) { return []; }

        $term_type      =   isset(request()->term_type) ? request()->term_type : 'Full';
        $type_term      =   null;

        if($term_type != 'Full'){
            $type_term = $term_type;
        }

        $subject_mapping  = SubjectMapping::where([ 
                                                'level_id'      => $section->level_id,  
                                                'curriculum_id' => $section->curriculum_id,
                                                'track_id'      => $section->track_id,
                                                'term_type'     => $term_type,
                                            ])->first();

        if(!$subject_mapping) { return []; }

        $subject_ids        =   collect($subject_mapping->subjects)->pluck('subject_code')->toArray();
        $subject_taken      =   TeacherSubject::where('school_year_id', SchoolYear::active()->first()->id)->where('section_id', request()->section_id)->get()->pluck('subject_id');

        $subjects       = SubjectManagement::whereIn('id', $subject_ids)
                                            ->whereIn('id', collect($this->teacher_subjects
                                                                    ->where('term_type', $type_term))
                                                                    ->where('section_id', request()->section_id)
                                                                    ->pluck('subject_id')->toArray())->get();
        // ->whereNotIn('id', $subject_taken);

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

    public function getClassCourse($id)
    {
        $course    =    OnlineCourse::with('subject', 'level', 'teacher', 'modules', 'modules.topics', 'share')
                                ->where('id', $id)
                                ->notArchive()
                                ->active()
                                ->first();
        return $course ?? null;      
    }

    /*
    |--------------------------------------------------------------------------
    | BIG BLUE BUTTON RECORDINGS
    |--------------------------------------------------------------------------
    */
    // public function getRecordings ($class_code)
    // {  
    //     $class      = self::getClass($class_code);

    //     if(!$class) {
    //         abort(403, 'Mismatch Class');
    //     }
    //     if($class->teacher_id != backpack_auth()->user()->employee_id && !backpack_user()->hasRole('School Head')) {
    //         abort(403, 'Mismatch Class');
    //     }

    //     // Check If User is Substitute Teacher Of Class and Video Status
    //     if($class){
    //         $class->isTeacherSubstitute   = 0;
           
    //         if($class->substitute_teachers){
    //             if(count($class->substitute_teachers)>0){
    //                 if(in_array(backpack_auth()->user()->employee_id, $class->substitute_teachers)){
    //                     $class->isTeacherSubstitute = 1;
    //                 }
    //             }
    //         }
    //     }
        
    //     $this->data['class']    =   $class;

    //     $recordingParams = new GetRecordingsParameters($class_code);
    //     $recordingParams->setMeetingId($class_code);

    //     $bbb = new BigBlueButton();
    //     $response = $bbb->getRecordings($recordingParams);

    //     $this->data['recordings'] = $response->getRawXml()->recordings->recording; 
    //     $this->data['crud'] = $this->crud;
    //     $this->data['entry'] = $this->crud->model::where('code', $class_code)->first();
    //     $this->data['class_attendance'] =   OnlineClassAttendance::getEmployeeAttendanceToday($class->id, backpack_user()->employee_id);

    //     if ($response->getReturnCode() == 'SUCCESS') {
    //         return view('onlineClass.myClasses.class_recordings', $this->data);
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
    public function getRecordings ($class_code)
    {
        $class = self::getClass($class_code);
        abort_if(!$class, 403, 'Mismatch Class');
        $substitute_teachers = collect($class->substitute_teachers)->toArray();

        if($class->teacher_id != backpack_auth()->user()->employee_id && !backpack_user()->hasRole('School Head')) {
            if(! in_array(backpack_auth()->user()->employee_id, $substitute_teachers)) {
                abort(403, 'Mismatch Class'); 
            }
        }

        $this->data['class']        =   $class;
        $this->data['recordings']   =   $class->zoomRecordings; 
        $this->data['crud']         =   $this->crud;
        $this->data['entry']        =   $this->crud->model::where('code', $class_code)->first();
        $this->data['class_attendance'] =   OnlineClassAttendance::getEmployeeAttendanceToday($class->id, backpack_user()->employee_id);

        return view('onlineClass.myClasses.zoom_recordings', $this->data);
    }

    public function searchMyClasses(Request $request)
    {
        $employee_id = backpack_auth()->user()->employee_id;
        if(backpack_auth()->user()->hasRole('School Head')){
            $classes    =   $this->crud->model::where('name', 'LIKE', '%' . $request->search . '%')
                                ->with([
                                    'section', 
                                    'teacher', 
                                    'subject',
                                    'course',
                                    'activeStudentSectionAssignment'
                                ])
                                ->join('employees', 'online_classes.teacher_id', '=', 'employees.id')
                                ->orWhere('online_classes.code', 'LIKE', '%' . $request->search . '%')
                                ->orWhere('employees.firstname', 'LIKE', '%' . $request->search . '%')
                                ->orWhere('employees.middlename', 'LIKE', '%' . $request->search . '%')
                                ->orWhere('employees.lastname', 'LIKE', '%' . $request->search . '%')
                                ->orderBy('online_classes.name')
                                ->activeSchoolYear()
                                ->notArchive()
                                ->active()
                                ->get();
            if($request->video_status == 'on-going')
            {
                $classes = $classes->where('ongoing', '=', 1)->paginate(8);
            }
            else{
                $classes = $classes->paginate(8);
            }
            $classes->setPath(url()->current());
            return response()->json(['classes' => $classes, 'user' => backpack_auth()->user()]);
        }
        else if(backpack_user()->hasRole('Teacher')){
            if($request->search == null){
                $classes    =   $this->crud->model::with([
                                    'section', 
                                    'teacher', 
                                    'subject',
                                    'course',
                                    'activeStudentSectionAssignment'
                                ])
                                ->where('teacher_id', backpack_auth()->user()->employee_id)
                                ->orWhere('substitute_teachers', 'like', "%\"{$employee_id}\"%")
                                ->orderBy('name')
                                ->activeSchoolYear()                                
                                ->notArchive()
                                ->active()
                                ->get();
            }
            else{    
                $classes    =   $this->crud->model::with([
                                    'section', 
                                    'teacher', 
                                    'subject',
                                    'course',
                                    'activeStudentSectionAssignment'
                                ])
                                ->where('teacher_id', backpack_auth()->user()->employee_id)
                                ->where('name', 'LIKE', '%' . $request->search . '%')
                                // ->orWhere('code', 'LIKE', '%' . $request->search . '%')
                                ->orderBy('name')
                                ->activeSchoolYear()                                
                                ->notArchive()
                                ->active()
                                ->get();
            }
            if($request->video_status == 'on-going')
            {
                $classes = $classes->where('ongoing', '=', 1)->paginate(8);
            }
            else{
                $classes = $classes->paginate(8);
            }
            $classes->setPath(url()->current());
            
            return response()->json(['classes' => $classes, 'user' => backpack_auth()->user()]);
        }
        return null;
    }

    public function showClassQuizzes($class_code)
    {
        $class      = self::getClass($class_code);

        if(!$class)
        {
            abort(403, 'Mismatch Class');
        }
        // Get Class Quizzes
        $class_quiz = OnlineClassQuiz::with('class', 'quiz', 'schoolYear')
                        ->where('online_class_id', $class->id)
                        ->get();
        dd($class_quiz->pluck('questions'));
    }

    public function setOngoing(Request $request)
    {
        $class = OnlineClass::where('id', $request->class_id)->first();
        if(!$class)
        {
            \Alert::error("Error, Unknown Class.")->flash();
            return redirect()->back();
        }

        if(!backpack_user()->hasRole('School Head'))
        {
            
            if(!in_array(backpack_auth()->user()->employee_id, $class->substitute_teachers ? $class->substitute_teachers : []) && backpack_auth()->user()->employee_id != $class->teacher_id)
            {
                \Alert::error("Unauthorized Access.")->flash();
                return redirect()->back();
            }
        }
        // If ongoing is 1 Set To 0
        if($class->ongoing){
            $class->ongoing = 0;
            if($class->update()){
                \Alert::success('Class Successfully Ended.')->flash();
                return redirect()->back();rect::to($this->crud->route);
            } else{
                \Alert::error('Error Ending, Something Went Wrong, Please Try Again.')->flash();
                return redirect()->back();
            }
        }
        else{
            $class->ongoing = 1;
            if($class->update()){
                \Alert::success('Class Successfully Started.')->flash();
                return redirect()->back();
            } else{
                \Alert::error('Error Starting, Something Went Wrong, Please Try Again.')->flash();
                return redirect()->back();
            }
        }
    }

    public function showRecordings()
    {

        dd('showRecordings');
        // $view = view('onlineClass.myClasses.recordings',compact('selected_topic', 'selected_topic_pages'))->render();
        // return response()->json(['html'=>$view]); 
    }

}
