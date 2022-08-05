<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

use App\Models\ParentStudent;
use App\Models\ParentUser;
use App\StudentCredential;

use App\Models\Student;
use App\Models\Rfid;
use App\Models\TurnstileLog;
use App\Models\StudentAccount;
use App\Models\Tuition;
use App\Models\Enrollment;
use App\Models\SpecialDiscount;
use App\Models\OtherProgram;
use App\Models\PaymentMethod;
use App\Models\PaymentMethodCategory;
use App\Models\Fund;

use App\Models\SchoolYear;
use App\Models\SectionManagement;
use App\Models\StudentSectionAssignment;
use App\Models\Requirement;

use App\AdditionalFee;
use App\PaymentHistory;
use App\SelectedOtherFee;
use App\SelectedPaymentType;
use App\SelectedOtherProgram;
use App\SelectedOtherService;
use App\Models\Discrepancy;
use App\Models\OtherService;

// For Grade
use App\Models\EncodeGrade;
use App\Models\Period;
use App\Models\SetupGrade;
use App\Models\SubjectMapping;
use App\Models\SubjectManagement;

use App\Http\Controllers\Student\EnrollmentTuitionController;

use App\Http\Controllers\OnlinePaymentController as PaymentController;

class StudentController extends Controller
{

    private $parent;
    private $user;
    private $student_classes;

    public function index2() {

        return view('studentPortal.dashboardv2');

    }

    /*
    |--------------------------------------------------------------------------
    | SHOW STUDENT INFORMATION
    |--------------------------------------------------------------------------
    */
    public function showStudentInformation($studentnumber)
    {
        $title          =   "Student Information";
        $parent         =   auth()->user()->parent;
        $student        =   Student::where('studentnumber', $studentnumber)->first();
        $schooltable    =   [];

        if(!$student)
        {
            abort(404, 'Student not found.');
        }

        $parentStudent  =   ParentStudent::where('parent_user_id', $parent->id)->where('student_id', $student->id)->first();

        if(!$parentStudent)
        {
            abort(401);
        }

        // GET SCHOOL TABLE UNTIL AND FROM
        if($student->schooltable)
        {
            if( count($student->schooltable) > 0 )
            {
                foreach ($student->schooltable as $key => $studentSchoolTable) 
                {
                    if($studentSchoolTable)
                    {
                        $grade_level_until  = YearManagement::where('id', $studentSchoolTable['grade_level_until'])->first();
                        $grade_level_from   = YearManagement::where('id', $studentSchoolTable['grade_level_from'])->first();
                        $schooltable[] = [
                            'grade_level_until' =>  $grade_level_until ? $grade_level_until->year : '-',
                            'grade_level_from'  =>  $grade_level_from ? $grade_level_from->year : '-',
                            'school_name'       =>  $studentSchoolTable['school_name'],
                            'year_attended'     =>  $studentSchoolTable['year_attended'],
                        ];
                    }
                }
            }
        }

        $student->schooltable = json_encode($schooltable);

        return view('parentPortal.student_information', compact(['title', 'parent', 'student']));

    }

    /*
    |--------------------------------------------------------------------------
    | SHOW STUDENT'S ENROLLMENTS
    |--------------------------------------------------------------------------
    */
    public function showStudentEnrollments($studentnumber)
    {
        $title                  =   "Student Information";
        $parent                 =   auth()->user()->parent;
        $student                =   Student::where('studentnumber', $studentnumber)->first();
        $this->studentnumber    =   $studentnumber;

        if(!$student)
        {
            abort(404, 'Student not found.');
        }

        $parentStudent          =   ParentStudent::where('parent_user_id', $parent->id)->where('student_id', $student->id)->first();

        if(!$parentStudent)
        {
            abort(401);
        }

        $title          =   "Enrollments";
        $enrollments    =   Enrollment::where('studentnumber', $studentnumber)
                                ->with('schoolyear')
                                ->with('department')
                                ->with('level')
                                ->with('track')
                                ->with('tuition')
                                ->with('commitmentPayment')
                                ->with(['studentSectionAssignment' => function ($query) {
                                    $query->where('students', 'like', '%' . $this->studentnumber . '%');
                                    $query->with(['section' => function ($q) {
                                        $q->with('level');
                                    }]);
                                }])
                                ->orderBy('created_at', 'ASC')
                                ->get();

        // $paymentController  =   new PaymentController();
        // $split_pay          =   PaymentMethodCategory::where('name', 'Split Pay')->first();
        // $paymentMethods     =   $split_pay 
        //                             ?   PaymentMethod::orderBy('name', 'ASC')
        //                                     ->where('payment_method_category_id', $split_pay->id)
        //                                     ->where('code', '!=', null)
        //                                     ->get() 
        //                             :   collect([]);

        // $data = [
        //     'fee'            => $paymentController->getFee(),
        //     'fixedAmount'    => $paymentController->getFixedAmount(),
        //     'paymentMethods' => $paymentMethods
        // ];

        // return view('parentPortal.student.enrolled_list', compact('enrollments', 'title', 'parent', 'student'))->with($data);
        return view('parentPortal.student.enrolled_list', compact('enrollments', 'title', 'parent', 'student'));
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW STUDENT'S ENROLLMENT'S TUITION
    |--------------------------------------------------------------------------
    */
    public function showTuition ($studentnumber, $enrollment_id)
    {

        $parent                 =   auth()->user()->parent;
        $student                =   Student::where('studentnumber', $studentnumber)->first();
        $this->studentnumber    =   $studentnumber;

        if(!$student)
        {
            \Alert::warning("Enrollment not found.")->flash();
            return redirect('parent/add-student');
        }

        $parentStudent          =   ParentStudent::where('parent_user_id', $parent->id)->where('student_id', $student->id)->first();

        if(!$parentStudent)
        {
            \Alert::warning("Enrollment not found.")->flash();
            return redirect('parent/add-student');
        }

        // Get Data From EnrollmentTuitionController / Same Data That Shows in Student
        $enrollmentTuition = new EnrollmentTuitionController();
        $data = $enrollmentTuition->allTuitionFeeData($enrollment_id);
        $data = $data->original;
        
        $total_mandatory_fees_upon_enrollment = 0;
        $total_payable_upon_enrollment        = 0;

        // Get Total Mandatory Fees Upon Enrollment
        if($data['tuition']->total_mandatory_fees_upon_enrollment)
        {
          if(count($data['tuition']->total_mandatory_fees_upon_enrollment) > 0)
          {
              foreach ($data['tuition']->total_mandatory_fees_upon_enrollment as $key => $mandatory_fee) {
                  if($mandatory_fee['payment_type'] == $data['enrollment']->commitment_payment_id)
                  {
                      $total_mandatory_fees_upon_enrollment = $mandatory_fee['amount'];
                  }
              }
          }
        }

        // Get Total Payable Upon Enrollment
        if($data['tuition']->tuition_fees)
        {
            if(count($data['tuition']->tuition_fees) > 0)
            {
                foreach ($data['tuition']->tuition_fees as $key => $tuition_fee) {
                    if($tuition_fee->payment_type == $data['enrollment']->commitment_payment_id)
                    {
                        $total_payable_upon_enrollment = $tuition_fee->total;
                    }
                }
            }
        }

        $data['total_mandatory_fees_upon_enrollment']   = $total_mandatory_fees_upon_enrollment;
        $data['total_payable_upon_enrollment']          = $total_payable_upon_enrollment;
        
        return  view('parentPortal.student.show_tuition', compact('data'));
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW STUDENT'S ENROLLMENT'S GRADES
    |--------------------------------------------------------------------------
    */
    public function showGrades($studentnumber, $enrollment_id)
    {
        $parent                 =   auth()->user()->parent;
        $student                =   Student::where('studentnumber', $studentnumber)->first();
        $this->studentnumber    =   $studentnumber;

        if(!$student)
        {
            abort(400, 'Student not found.');
        }

        $parentStudent          =   ParentStudent::where('parent_user_id', $parent->id)->where('student_id', $student->id)->first();

        if(!$parentStudent)
        {
            abort(401);
        }
        // dd($studentnumber, $enrollment_id);
        // abort(400, '<b style="color: #dd4b39;">Page Under Construction.</b>');

        $section    =   null;
        $title      =   "Grades";
        $studentnumber = $student->studentnumber;
        $enrollment = Enrollment::where('id', $enrollment_id)
                                ->where('studentnumber', $studentnumber)
                                ->with('schoolYear')
                                ->with(['studentSectionAssignment' => function ($query) use ($studentnumber) {
                                    $query->where('students', 'like', '%' . $studentnumber . '%');
                                    $query->with(['section' => function ($q) {
                                        $q->with('level');
                                    }]);
                                }])->first();

        if($enrollment === null) {
            \Alert::warning("Enrollment not found.")->flash();
            return redirect('parent/student-enrollments/' . $studentnumber);
        }
                                
        $subjectMapping = SubjectMapping::where('level_id', $enrollment->level_id)->with('level')->first();

        if($subjectMapping === null) {
            \Alert::warning("Error")->flash();
            return redirect('parent/student-enrollments/' . $studentnumber);
        }

        $subjectsIds = collect($subjectMapping->subjects)->pluck('subject_code')->toArray();
        $subjects       = SubjectManagement::findMany($subjectsIds);
        if($enrollment->studentSectionAssignment)
        {
            if($enrollment->studentSectionAssignment->section)
            {
                $section        = SectionManagement::where('id', $enrollment->studentSectionAssignment->section->id)->with('level')->first();
            }
        }
        $schoolYear     = SchoolYear::where('id', $enrollment->school_year_id)->first();
        $periods        = [];

        if($section !== null) {
            if($section->level)
            {
                $periods = Period::where('department_id', $section->level->department_id)->orderBy('sequence', 'asc')->get();
            }
        }
        else{
            \Alert::warning("Section not found.")->flash();
            return redirect('parent/student-enrollments/' . $studentnumber);
        }

        $grades = EncodeGrade::getGrades($student->studentnumber, $enrollment->level_id, $enrollment->studentSectionAssignment->section->id, count($periods));
        return view('parentPortal.student.show_grade', compact('student', 'enrollment', 'grades', 'subjects', 'subjectMapping', 'periods', 'schoolYear', 'section', 'title'));
    }


}
