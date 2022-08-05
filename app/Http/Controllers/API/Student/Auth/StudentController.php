<?php

namespace App\Http\Controllers\API\Student\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

// MODELS
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Tuition;

use App\Models\OnlineTopicPage;

use App\Models\OtherProgram;
use App\Models\OtherService;
use App\Models\Rfid;
use App\Models\CommitmentPayment;
use App\Models\TurnstileLog;

use App\Models\Fund;
use App\Models\FundinTransaction;
use App\Models\SectionManagement;
use App\Models\SubjectMapping;
use App\Models\EncodeGrade;
use App\Models\SubjectManagement;
use App\Models\Period;
use App\Models\SchoolYear;
use App\Models\SpecialDiscount;

use App\SelectedOtherProgram;
use App\SelectedOtherService;

use App\PaymentHistory;
use App\StudentCredential;

use Illuminate\Support\Facades\DB;

use App\Http\Controllers\API\Helper;


use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function profile ()
    {
    	// $studentnumber 	= request()->user()->studentnumber;
    	// $student 		= Student::where('studentnumber', $studentnumber)->first();
        // return $student;
        return response()->json(StudentController::getProfile(request()->user()->studentnumber));
    }

    public function accounts ()
    {
    	// return request()->user()->studentnumber;
    	$studentnumber = request()->user()->studentnumber;
        $enrollments_year = Enrollment::where('studentnumber', $studentnumber)
                                        ->with('schoolYear:id,schoolYear,start_date,end_date,isActive')
                                        ->with([
                                        			'level' => function ($q) {
                                        				$q->select('id', 'year','department_id');
                                    			    	$q->with('department:id,name');
                                    			    }
                                    	])
                                        ->with('commitmentPayment:id,name,additional_fee')
                                     //    ->with('section')
                                        ->with('tuition')
                                        ->get();

        return response()->json($enrollments_year);
    }


    public function account ($id)
    {
    	$studentnumber 	= request()->user()->studentnumber;
    	$_enrollment 	= Enrollment::where('id', $id)
                                    ->with('student')
                                    ->with(['tuition' => function ($q) {
                                        $q->pluck('id', 'form_name');
                                    }])
                                    ->with('additionalFees')
                                    ->with('schoolYear')
                                    ->with('commitmentPayment:id,name,additional_fee')
                                    ->first();

        if($_enrollment == null) {
            return "Student Number " . $studentnumber . " Not Found";
        }
        
        $tuition = $_enrollment->tuition;

        $selected_other_programs = SelectedOtherProgram::where('enrollment_id',$_enrollment->id)->with('user')->with('otherProgram')->get();

        $total_selected_other_program = 0;
        foreach ($selected_other_programs as $selected_other_program) {
            $total_selected_other_program += $selected_other_program->otherProgram->amount;
        }

        $selected_other_services = SelectedOtherService::where('enrollment_id',$_enrollment->id)->with('otherServices')->get();

        $total_selected_other_service = 0;
        foreach ($selected_other_services as $selected_other_service) {
            $total_selected_other_service += $selected_other_service->otherService->amount;
        }

        $other_program_lists = OtherProgram::where('qbo_id', '!=', null)->get();
        $other_service_lists = OtherService::where('qbo_id', '!=', null)->get();


        $special_discounts_lists = SpecialDiscount::where('enrollment_id',$_enrollment->id)->with('user')->get();
        $total_special_discount = 0;
        foreach ($special_discounts_lists as $special_discounts_list) {
            $total_special_discount += $special_discounts_list->amount;
        }

        $payment_histories =  PaymentHistory::where('enrollment_id',$_enrollment->id)->with('user')->with('paymentMethod')->get();

        $total_payment_history = 0;
        foreach ($payment_histories as $payment) {
            $total_payment_history += $payment->amount;
        }


        $tuition_list = [
            'enrollment_id'                 => $_enrollment->id,
            'commitment_payment'            => $_enrollment->commitmentPayment,
            'additional_fees'               => $_enrollment->additional_fees,
            'tuition'                       => $tuition,
            'selected_other_programs'       => $selected_other_programs,
            'selected_other_services'       => $selected_other_services,
            'total_selected_other_program'  => $total_selected_other_program,
            'total_selected_other_service'  => $total_selected_other_service,
            'other_program_lists'           => $other_program_lists,
            'other_service_lists'           => $other_service_lists,
            'special_discount_lists'        => $special_discounts_lists,
            'total_special_discount'        => $total_special_discount,
            'payment_histories'             => $payment_histories,
            'total_payment_history'         => $total_payment_history
        ];

        return response()->json($tuition_list);   
    } 

    public function attendanceLogs (Request $request)
    {
       $rfid_val = StudentController::getStudentRfid(request()->user()->studentnumber);
       
        return response()->json(StudentController::getAttendanceInfo($rfid_val,8,
        $request->input('from'), $request->input('to')));
    }
    
    public function attendanceLogs2 (Request $request)
    {
       $rfid_val = StudentController::getStudentRfid(request()->user()->studentnumber);
       
        return response()->json(StudentController::getAttendanceInfo2($rfid_val,8,
        $request->input('from'), $request->input('to')));
    }

    public function tapIn(Request $request) {

        $rfid_val = StudentController::getStudentRfid(request()->user()->studentnumber);
        $timein = $request->input('taptime');
        $q = "INSERT INTO system_attendances(rfid, time_in, time_out, created_at) 
              SELECT '$rfid_val', '$timein', NULL, CURRENT_TIMESTAMP";

        DB::insert(DB::raw($q), []);

        return response()->json([], 204);

    }

    public function tapOut(Request $request) {

        $rfid_val = StudentController::getStudentRfid(request()->user()->studentnumber);
        $timeout = $request->input('taptime');
        $q = "UPDATE system_attendances SET time_out = '$timeout' 
        WHERE rfid = '$rfid_val' AND CONVERT(now(), DATE) = CONVERT(created_at, DATE)";

        DB::update(DB::raw($q), []);

        return response()->json([], 204);

    }

    public function attendanceToday()
    {               

        $q = "SELECT 
            COALESCE(MIN(A.timein), NULL) as tap_in, 
            COALESCE(MAX(A.timeout), NULL) as tap_out
         FROM turnstile_logs A 
         WHERE 
            rfid = (SELECT rfid FROM rfids WHERE studentnumber = :studentnumber)
         AND 
            CONVERT(now(), DATE) = CONVERT(created_at, DATE)"; 
        
        $result = DB::select(DB::raw($q), ['studentnumber' => request()->user()->studentnumber])[0];
        
        return response()->json(["tap_in" => $result->tap_in, 
                                 "tap_out" => $result->tap_out]);

    }

    public function attendanceToday2()
    {               

        $q = "SELECT 
            COALESCE(MIN(A.time_in), NULL) as tap_in, 
            COALESCE(MAX(A.time_out), NULL) as tap_out
         FROM system_attendances A 
         WHERE 
            rfid = (SELECT rfid FROM rfids WHERE studentnumber = :studentnumber)
         AND 
            CONVERT(now(), DATE) = CONVERT(created_at, DATE)"; 
        
        $result = DB::select(DB::raw($q), ['studentnumber' => request()->user()->studentnumber])[0];
        
        return response()->json(["tap_in" => $result->tap_in, 
                                 "tap_out" => $result->tap_out]);

    }



    public function attendanceLogsRange($start_date, $end_date)
    {
        $rfid = Rfid::where('studentnumber', request()->user()->studentnumber)->first();

        if($rfid !== null)
        {
            $logs = TurnstileLog::where('rfid', $rfid->rfid)->whereDate('created_at', '>=', Carbon::parse($start_date))->whereDate('created_at', '<=', Carbon::parse($end_date))->get();
            return response()->json($logs);
        }

        return response()->json(null);
    }

    public function invoice ()
    {
        return ("INVOICE");
    }

    public function funds(){
               
        $rfid_val = StudentController::getStudentRfid(request()->user()->studentnumber);
        //return response()->json(["resp" => $rfid_val]);
         return response()->json(StudentController::getFundsInfo($rfid_val,12));  

    }

    public static function getStudentRfid($studentnumber)
    {
        $q = DB::table('rfids')->select('rfid')
        ->where('studentnumber', '=', "$studentnumber")
        ->get();

        return (count($q) > 0) ? $q[0]->rfid : null;


    }

    public function updatePassword ()
    {
        // if(request()->has('studentnumber')) {
            $studentnumber  = request()->user()->studentnumber;

            $update = StudentCredential::where('studentnumber', $studentnumber)
                                        ->update([
                                            'password' => bcrypt(request()->password), 
                                            'is_first_time_login' => 0
                                        ]);
            if($update) {
                return response()->json(['message' => 'Successfully Updated Password', 'status' => 'OK'], 200);
            }
            return response()->json(['message' => 'Error Updating Password, Please Try Again.', 'status' => 'ERROR'], 422);
        
        // }
        // return response()->json(['message' => 'Missing Required Parameters.', 'status' => 'ERROR'], 422);
    }

    // GRADES
    public function getAllSchoolYearEnrolled ()
    {
        $studentnumber = request()->user()->studentnumber;
        return StudentController::getGradesList($studentnumber);
    }

    public static function getGradesList($studentnumber)
    {
        $enrollments = Enrollment::where('studentnumber', $studentnumber)
                                    ->with('schoolYear')
                                    ->with(['studentSectionAssignment' => function ($query) use ($studentnumber) {
                                        $query->where('students', 'like', '%' . $studentnumber . '%');
                                        $query->with(['section' => function ($q) {
                                            $q->with('level');
                                        }]);
                                    }])->get();

        return ['data' => $enrollments, 'error' => false, 'message' => null];
    }

    public static function getGradeRecords($studentnumber, $level_id, $section_id, $school_year_id)
    {
        
        $subjectMapping = SubjectMapping::where('level_id', $level_id)->with('level')->first();

        if($subjectMapping === null) {
            return response()->json(['data' => null, 'error' => true, 'message' => 'Subject Mapping Is Empty']);
        }

        $subjectIds = collect($subjectMapping->subjects)->pluck('subject_code')->toArray();

        $subjects       = SubjectManagement::findMany($subjectIds);
        $section        = SectionManagement::where('id', $section_id)->with('level')->first();
        $schoolYear     = SchoolYear::where('id', $school_year_id)->first();
        $periods        = [];

        if($section !== null) {
            $periods = Period::where('department_id', $section->level->department_id)->orderBy('sequence', 'asc')->get();
        }

        $grades = EncodeGrade::getGrades($studentnumber, $level_id, $section_id, count($periods));

        $data = [
            'subjects' => $subjects,
            'section' => $section,
            'schoolYear' => $schoolYear,
            'periods' => $periods,
            'grades' => $grades,
        ];

        return ['data' => $data, 'error' => false, 'message' => null];
    }

    public function viewGrades ()
    {
        $studentnumber = request()->user()->studentnumber;
        return StudentController::getGradeRecords($studentnumber,
        request()->level_id,request()->section_id,request()->school_year_id);
    }

    public function getGrades()
    {
        $studentnumber = request()->user()->studentnumber;
        $gradesList = json_decode(json_encode(StudentController::getGradesList($studentnumber)));
        $reverse_gradesList = array_reverse($gradesList->data);
        $result = [];
        foreach($reverse_gradesList as $i){
            $level_id = $i->level_id;
            $school_year_id = $i->school_year_id;
            if(!isset($i->student_section_assignment)){
                continue;
            }
            $section_id = $i->student_section_assignment->section_id;

            $gradeRecords = json_decode(json_encode(StudentController::getGradeRecords($studentnumber,$level_id,$section_id,$school_year_id)));
            $row = [];
            $row['school_year'] = $i->school_year_name;
            $row['section_name'] = $i->student_section_assignment->section->name_level;
            $row['subjects'] = $gradeRecords->data->subjects;
            $row['periods'] = $gradeRecords->data->periods;
            $row['grades'] = [];

            foreach($gradeRecords->data->grades as $j){
                $_grades = [];
                if(isset($j->subjects)){
                    foreach($j->subjects as $k){
                        array_push($_grades, $k->initial_grade);
                    }
                }

                $_grades_count = count($_grades);
                for($l = $_grades_count; $l < count($row['periods']); $l++){                    
                    array_push($_grades, 0);
                }
                
                if(isset($j->final_grade)){
                    array_push($_grades, $j->final_grade);
                }else{
                    array_push($_grades, 0);
                }

                array_push($row['grades'], $_grades);              
                

            }

            $subjects_count = count($row['subjects']);
            $grades_count = count($row['grades']);
            for($si = $subjects_count; $si > $grades_count; $si--){
                array_pop($row['subjects']);
            }

            array_push($result, $row);
        }

       return response()->json(['data' => $result]);
     // return response()->json(['data' => []]);
    }
    //endGradesNew

    public function onlineClasses()
    {
        $student = request()->user()->student;
        $online_classes = Student::getOnlineClasses($student->id);
        return $online_classes ? $online_classes : [];
        // OLD API
        // return self::getOnlineClasses(request()->user()->studentnumber);
    }
    
    public function nextEligibleEnrollment()
    {
        $q = "SELECT A.payment_types, COALESCE(B.next_eligible_enrollments, 
        JSON_ARRAY(JSON_OBJECT('department_id', null, 
        'level_id', null, 'school_year_id',null, 
        'track_id', null, 'term_type', null))) as next_eligible_enrollments
        FROM (SELECT JSON_ARRAYAGG(JSON_OBJECT('id',id,'name',name)) as payment_types 
        FROM commitment_payments) A 
        JOIN (SELECT JSON_ARRAYAGG(JSON_OBJECT('department_id',A.department_id, 
            'level_id',A.id, 'level_name', A.year ,'school_year_id',B.school_year_id,
            'previous_track_id', C.track_id,'term_type', C.term_type,
            'previous_department_id',B.department_id, 'new_term_type', B.term,
            'department_name', D.name, 'school_year_name', E.schoolYear)) as next_eligible_enrollments
        FROM year_managements A 
        JOIN enrollment_statuses B ON B.department_id = A.department_id
        JOIN (SELECT track_id, term_type from enrollments 
        WHERE studentnumber = :year_management_studentnumber ORDER BY level_id DESC limit 1) C ON 1=1
        JOIN departments D on A.department_id = D.id
        JOIN school_years E on E.id = B.school_year_id 
        WHERE sequence = (SELECT sequence+1 FROM year_managements WHERE 
                         (SELECT MAX(level_id) as level_id FROM enrollments 
                         WHERE studentnumber = :enrollment_studentnumber) = id)
        AND ((now() BETWEEN B.start_date AND B.end_date) OR B.early_enrollment_status = 1)) B";

        $studentnumber = request()->user()->studentnumber;
        $result1 = DB::select(DB::raw($q), ['year_management_studentnumber' => $studentnumber,
                                           'enrollment_studentnumber' => $studentnumber])[0];   
        $payment_types = json_decode($result1->payment_types);
        $next_eligible_enrollments = json_decode($result1->next_eligible_enrollments)[0];
        
        $q = "SELECT JSON_ARRAYAGG(JSON_OBJECT('tuition_id',A.id,'track_id', A.track_id,'track_code', B.code)) as tuitions
        FROM tuitions A 
        LEFT JOIN track_managements B on A.track_id = B.id
        WHERE schoolyear_id = :school_year_id AND department_id = :department_id AND grade_level_id = :level_id";

        $result2 = DB::select(DB::raw($q), ['school_year_id' => $next_eligible_enrollments->school_year_id,
                                            'level_id' => $next_eligible_enrollments->level_id,
                                            'department_d' => $next_eligible_enrollments->department_id
        ])[0];

        return response()->json([
            "payment_types" => $payment_types,
            "next_eligible_enrollments" => $next_eligible_enrollments,
            "tuitions" => json_decode($result2->tuitions)
        ]);
    }

    public static function getProfile($studentnumber){

        $q = "SELECT 
        CONCAT(A.lastname,', ', A.firstname, IF(A.middlename is null OR A.middlename = '', '', CONCAT(' ',LEFT(A.middlename,1), '.'))) as fullname,
        A.gender,
        A.birthdate,
        A.age,
        A.studentnumber,
        IF(A.photo is null, 'images/headshot-default.png', CONCAT('storage/', A.photo)) as photo,
        B.schoolYear as school_year,
        C.name as department,
        D.year as grade_level,
        COALESCE(E.code, null) as track,

        IF(A.fatherfirstname is null OR A.fatherfirstname = '', '', 
        CONCAT(A.fatherlastname, ', ', A.fatherfirstname, IF(A.fathermiddlename is null OR A.fathermiddlename = '', '', CONCAT(' ',LEFT(A.fathermiddlename,1), '.')))
        ) as father_fullname,
        A.fatherofficenumber as father_office,
        A.fatherMobileNumber as father_mobile,

        IF(A.motherfirstname is null OR A.motherfirstname = '', '', 
        CONCAT(A.motherlastname, ', ', A.motherfirstname, IF(A.mothermiddlename is null OR A.mothermiddlename = '', '', CONCAT(' ',LEFT(A.mothermiddlename,1), '.')))
        ) as mother_fullname,
        A.motherOfficeNumber as mother_office,
        A.mothernumber as mother_mobile,

        IF(A.asthma = 1, 'Yes', 'No') as asthma,
        IF(A.asthmainhaler = 1, 'Yes', 'No') as asthmainhaler,
        IF(A.allergies = 1, 'Yes', 'No') as allergies,
        IF(A.drugallergy = 1, 'Yes', 'No') as drugallergy,
        IF(A.visionproblem = 1, 'Yes', 'No') as visionproblem,

        COALESCE(A.previousschool, 'N/A') as previous_school,
        COALESCE(A.previousschooladdress, 'N/A') as previous_school_address

        FROM students A 
        LEFT JOIN school_years B ON A.schoolyear = B.id
        LEFT JOIN departments C ON A.department_id = C.id
        LEFT JOIN year_managements D ON A.level_id = D.id
        LEFT JOIN track_managements E ON A.track_id = E.id
        WHERE studentnumber = :studentnumber";

        return DB::SELECT(DB::RAW($q), ['studentnumber' => $studentnumber])[0];       

    }

    public static function getFundsInfo($rfid,$perpage)
    {
        if(is_null($rfid))
        {
            return ['remaining_funds' => 0.00,
            'fund_in_transactions' => null];
        }

        $res = FundinTransaction::select('amount_tendered','created_at')->where('rfid', '=', $rfid)->
        // where('amount_tendered', '=', '-1')->
        orderBy('created_at', 'desc')->simplePaginate($perpage);        
        
        $q = "SELECT COALESCE(fund,0.00) as remaining_funds FROM funds WHERE rfid_id = :rfid";
        $fund_res = DB::SELECT(DB::RAW($q), ['rfid' => $rfid]);
        if($fund_res){
            $fundResult = $fund_res[0]->remaining_funds;
        } else{
            $fundResult = 0.00;
        }

        return ['remaining_funds' => $fundResult,
        'fund_in_transactions' => $res];
    }           

    public static function getAttendanceInfo($rfid,$perpage,$from,$to)
    {
        
        if(is_null($rfid))
        {
            return ['tap_ins' => null];
        }

        $res = DB::table('turnstile_logs')
        ->selectRaw('min(timein) as tapin, max(timeout) as tapout, Date(created_at) as attendance_date')
        ->whereRaw("rfid = $rfid AND (Date(created_at) BETWEEN '$from' AND '$to')")
        ->orderBy('created_at', 'desc')
        ->groupBy(DB::raw('attendance_date'))
        ->simplePaginate($perpage);     

        return ['tap_ins' => $res];
    }

    public static function getAttendanceInfo2($rfid,$perpage,$from,$to)
    {
        
        if(is_null($rfid))
        {
            return ['tap_ins' => null];
        }

        $res = DB::table('system_attendances')
        ->selectRaw('min(time_in) as tapin, max(time_out) as tapout, Date(created_at) as attendance_date')
        ->whereRaw("rfid = $rfid AND (Date(created_at) BETWEEN '$from' AND '$to')")
        ->orderBy('created_at', 'desc')
        ->groupBy(DB::raw('attendance_date'))
        ->simplePaginate($perpage);     

        return ['tap_ins' => $res];
    }

    public function onlineCourse(Request $request)
    {
        $studentnumber 	= request()->user()->studentnumber;
        return response()->json(StudentController::getOnlineCourse($request->input('oci')));
    }   

    public static function getOnlineCourse($oci)
    {

        $q = 'SELECT A.code as meetingId, A.duration as duration,

        (SELECT JSON_ARRAYAGG(JSON_OBJECT("title", title, "description", description ,"topics", 

        (SELECT JSON_ARRAYAGG(JSON_OBJECT("id", id, "title", title, "pages", 
        
        (SELECT JSON_ARRAYAGG(id) FROM online_topic_pages D WHERE C.id = D.online_class_topic_id))) 

        FROM online_class_topics C
        WHERE B.id = C.online_class_module_id))) 

        FROM online_class_modules B
        WHERE A.id = B.online_course_id) as lessons,

        CONCAT(
            CONCAT(A.description, "<br>") , 
            CONCAT("<h2>Requirements</h2>",COALESCE(CONCAT(A.requirements, "<br>"),"<p>No Content</p>")), 
            CONCAT("<h2>Content Standard</h2>",COALESCE(CONCAT(A.content_standard, "<br>"),"<p>No Content</p>")), 
            CONCAT("<h2>Performance Standard</h2>",COALESCE(A.performance_standard,"<p>No Content</p>"))
        ) as information

        FROM online_courses A
        WHERE A.id=:oci';            

        $result = DB::select(DB::raw($q), ['oci' => $oci]);

        if(empty($result))
        {
            return ["data" => null];
        }

        $result[0]->lessons = json_decode($result[0]->lessons);
        //$result[0]->isMeetingRunning = Helper::isBBBMeetingRunning($result[0]->meetingId);
                
        return ["data" => $result[0]];

    }

    public static function getOnlineClasses($studentnumber)
    {
        $q = "SELECT A.name, A.code, A.id, A.color, A.online_course_id,
        CONCAT(
        IF(B.prefix is null OR B.prefix = '', '', 
        CONCAT(B.prefix, '. ')),
        B.lastname,', ', 
        B.firstname, 
        IF(B.middlename is null OR B.middlename = '', '', 
        CONCAT(' ',LEFT(B.middlename,1), '.'))) as teacher_fullname
        FROM schoolmate_demo.online_classes A 
        JOIN employees B ON B.id = A.teacher_id
        WHERE section_id = (SELECT section_id FROM student_section_assignments 
        WHERE JSON_CONTAINS(students, :studentnumber))";

        $result = DB::SELECT(DB::RAW($q), ["studentnumber"=>$studentnumber]);

        foreach($result as $class){
            $class->isMeetingRunning = Helper::isBBBMeetingRunning($class->code)->running;
            //$class->isMeetingRunning = true;
        }

        return response()->json(["classes" => $result]);
    }

    public function lessonPage($id)
    {
        $topicpage = OnlineTopicPage::find($id, ['title','description']);

        if(is_null($topicpage)){
            return response()->json("Record not found", 404);
        }

        return response()->json($topicpage, 200);
    }

    public function capturePayment(Request $request)
    {
        $studentnumber = request()->user()->studentnumber;
        
        $description  = $request->description;
        $enrollment_id = $request->enrollment_id;
        $file  = $request->file("File");
        $file_path = 'mobile_captured_payments/capture_' . time() . '.jpg';
        
        $path = $file->storeAs('public',$file_path);
        
        $q = "INSERT INTO mobile_captured_payments (studentnumber, path_captured_photo, 
        description, enrollment_id,created_at) 
        SELECT :studentnumber, 
        CONCAT('storage/',:pathcaptured),
        :description, :enrollmentId,
        now()";

        DB::insert(DB::raw($q),['studentnumber' => $studentnumber, 'pathcaptured' => $file_path,
        'description' => $description, 'enrollmentId' => $enrollment_id]);
        
        return response()->json(['message' => 'success', 'path' => $path], 200);
    }

    public function incompleteEnrollments(){
        $studentnumber = request()->user()->studentnumber;
        return self::getIncompleteEnrollments($studentnumber);
    }

    public static function getIncompleteEnrollments($studentnumber){

        $result = DB::select(DB::RAW(
            "SELECT A.id, 
                    B.name as department_name,
                    C.schoolYear as school_year_name
             FROM      enrollments A
             LEFT JOIN departments B ON A.department_id = B.id
             LEFT JOIN school_years C ON A.school_year_id = C.id
             WHERE 
             studentnumber = :studentnumber 
             AND
             A.require_payment = 1 
             AND
             (A.proof_of_payment is null OR A.proof_of_payment = '')
             AND 
             NOT EXISTS (SELECT 1 FROM mobile_captured_payments 
                     WHERE studentnumber = :studentnumber_sub 
                     AND A.id = enrollment_id)"
        ), 
        ['studentnumber' => $studentnumber, 'studentnumber_sub' => $studentnumber]);

        return response()->json(['data' =>$result]);
    }

    public function joinMeeting(Request $request){
        $meetingId = $request->input('meetingId');

        return response()->json(['url'=>Helper::joinBBBMeeting($meetingId, 
        StudentController::getProfile(request()->user()->studentnumber)->fullname
        )]);
    }

}
