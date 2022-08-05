<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Student;
use App\Models\Enrollment;

use App\Models\SchoolYear;
use App\Models\Department;
use App\Models\TermManagement;
use App\Models\YearManagement;
use App\Models\TrackManagement;
use App\Models\CommitmentPayment;
use App\Models\Tuition;

use App\Models\OtherProgram;
use App\Models\OtherService;

use App\SelectedOtherProgram;
use App\SelectedOtherService;

use App\Models\KioskSetting;
use App\Models\KioskEnrollment;
use App\Models\EnrollmentStatus;
use App\Models\EnrollmentStatusItem;

use App\Mail\SendMailableKioskAfterEnrolled;
use App\Mail\SendMailableKioskNewStudent;
use Mail;

use Carbon\Carbon;

class EnrollmentController extends Controller
{
	public function showEnrollment($term_type)
	{
		$term_type 	= strtolower($term_type);
		$student    = auth()->user()->student;
		$enrollment = null;

		if($term_type == 'summer') {
			$enrollment  = $this->getStudentSummerEnrollment($student->id);
		} else {
			$enrollment  = $this->getStudentNextEnrollment($student->id);
		}
		abort_if(! $enrollment, 404);
		$enrollment = (object)$enrollment;

		if(strtolower($enrollment->term_type) != $term_type) {
			abort(404);
		}

		$data = [
			'enrollment' => $enrollment
		];
		return view('studentPortal.enrollment.enroll')->with($data);
	}

	public function submitEnrollment(Request $request, $term_type)
	{
		$term_type 	= strtolower($term_type);
		$student    = auth()->user()->student;
		$enrollment = null;

		if($term_type == 'summer') {
			$enrollment  = $this->getStudentSummerEnrollment($student->id);
		} else {
			$enrollment  = $this->getStudentNextEnrollment($student->id);
		}
		abort_if(! $enrollment, 404);
		$enrollment = (object)$enrollment;

		if(strtolower($enrollment->term_type) != $term_type) {
			abort(404);
		}

		$show_tuition  		= KioskSetting::where('key', 'tuition')->first();
		$commitmentPayment  = CommitmentPayment::where('id', $request->commitment_payment_id)->first();

        $enrollment_track   = $enrollment->track;

        if(isset($enrollment->department_tracks)) {
            if(count($enrollment->department_tracks)>0) {
                if(!$enrollment_track) {
                    $enrollment_track = TrackManagement::where('id', $request->track_id)->where('level_id', $enrollment->level->id)->first();
                }
            }
        }

		$newEnrollment                        = new Enrollment;
        $newEnrollment->student_id            = $student ? $student->id : null;
        $newEnrollment->studentnumber         = $student->studentnumber;
        $newEnrollment->tuition_id            = null;
        $newEnrollment->school_year_id        = $enrollment->schoolYear->id;
        $newEnrollment->department_id         = $enrollment->department->id;
        $newEnrollment->level_id              = $enrollment->level->id;
        $newEnrollment->track_id              = $enrollment_track ? $enrollment_track->id : null;
        $newEnrollment->curriculum_id         = null;
        $newEnrollment->commitment_payment_id = $commitmentPayment ? $commitmentPayment->id : null;
        $newEnrollment->term_type             = $enrollment->term_type;
        $newEnrollment->is_applicant          = 1;
        $newEnrollment->old_or_new            = 'old';

        $oldEnrollment = Enrollment::where('student_id', $newEnrollment->student_id)
                            ->where('studentnumber', $newEnrollment->studentnumber)
                            ->where('school_year_id', $newEnrollment->school_year_id)
                            ->where('department_id', $newEnrollment->department_id)
                            ->where('level_id', $newEnrollment->level_id)
                            ->where('term_type', $newEnrollment->term_type)
                            ->where('track_id', $newEnrollment->track_id)
                            ->where('term_type', $newEnrollment->term_type)
                            ->first();

        if($oldEnrollment) {
        	\Alert::warning("Account is already enrolled!")->flash();
        	return redirect()->back();
        }

        if($newEnrollment->save()) {

            $kioskEnrollment                 = new KioskEnrollment;
            $kioskEnrollment->kiosk_id       = uniqid();
            $kioskEnrollment->student_id     = $student ? $student->id : null;
            $kioskEnrollment->enrollment_id  = $newEnrollment->id;
            $kioskEnrollment->email          = $request->email;
            $kioskEnrollment->student_status = 'old';
            $kioskEnrollment->save();

            $tuition = Tuition::where('id', $request->tuition_id)->first();

            try {
                Mail::to($request->email)->send(new SendMailableKioskAfterEnrolled($newEnrollment, $tuition, $kioskEnrollment, $show_tuition ? $show_tuition->active : null));
            } catch (Exception $e) {

            }

            \Alert::success("Account is successfully enrolled!")->flash();
        	return redirect()->back();
        } else {
        	\Alert::error("Something Went Wrong, Please Try To Reload The Page.")->flash();
        	return redirect()->back();
        }

	}

    public function getStudentNextEnrollment($student_id)
    {
        $student    = Student::where('id', $student_id)->first();

        if(! $student->studentnumber) {
            return null;
        }

        $latest_enrollment = Enrollment::where('studentnumber', $student->studentnumber)->where('term_type', '!=', 'Summer')->latest()->first();

        if(! $latest_enrollment) { 
            return null; 
        }

        $term 			= TermManagement::where('department_id', $latest_enrollment->department_id)->first();
        // $enrollments 	= Enrollment::where('studentnumber', $student->studentnumber)->get();
        $nextEnrollment = null;

        /*
        |--------------------------------------------------------------------------
        | SEMESTER TYPE NEXT ENROLLMENT
        |--------------------------------------------------------------------------
        */
        if($term->type == "Semester") {
        	// Check The Maximum Term And The Term Of Previous Enrollment
            $previousTerm = ['index'=>null, 'term'=>null];
            $maxTerm 	  = ['index'=>null, 'term'=>null];

            if(count($term->ordinal_terms)>0) {
                foreach ($term->ordinal_terms as $key => $value) {
                    if($value == $latest_enrollment->term_type)
                    {
                        $previousTerm['index'] = $key;
                        $previousTerm['term'] = $value;
                    }
                    // GET LAST TERM OF THE TERM
                    $maxTerm['index'] = $key;
                    $maxTerm['term'] = $value;

                }
            }
            // GET THE INDEX OF NEXT TERM
            $index = $previousTerm['index']+1;

            // FIRST TERM
            if($maxTerm['index'] < $index) {
            	// Get The Next Grade Level
                $nextGradeLevel     = YearManagement::where('sequence', $latest_enrollment->level->sequence + 1)->first();

                // RETURN NULL IF THERE IS NO NEXT GRADE LEVEL
                if(!$nextGradeLevel) {
                	return null;
                }

                $enrollment_status 		 = 	EnrollmentStatus::where('department_id', $nextGradeLevel->department_id)->where('summer', 0)->get();
            	$enrollment_status_items = 	EnrollmentStatusItem::whereIn('enrollment_status_id', $enrollment_status->pluck('id'))
	            								->where('term', '!=', 'Summer')
	            								->where('term', 'First')
	            								->active()
	            								->first();

	           	if(!$enrollment_status_items) {
                	return null;
                }

                $nextEnrollment['schoolYear'] 	= 	$enrollment_status_items->enrollment_status->schoolYear;
                $nextEnrollment['department'] 	= 	$nextGradeLevel->department;
                $nextEnrollment['level'] 		= 	$nextGradeLevel;
                $nextEnrollment['curriculum'] 	= 	$latest_enrollment->curriculum;
                $nextEnrollment['term_type'] 	= 	"First";
                $nextEnrollment['track'] 		= 	$latest_enrollment->track 
                										? TrackManagement::where([
                                                            'code' => $latest_enrollment->track->code,
                                                            'level_id' => $nextGradeLevel->id
                                                        ])->first() 
                                                        : null;

                // GET NEXT GRADE LEVEL'S TRACKS
                if($nextGradeLevel->department->with_track && $nextEnrollment['track'] == null) {
                    $departmentTracks = TrackManagement::where('level_id', $nextGradeLevel->id)->get();
                }

                return $nextEnrollment;
            }

            // NEXT TERM OR HIGHER THAN FIRST TERM
            $nextTerm = array_values($term->ordinal_terms)[$index];

            $enrollment_status 		 = 	EnrollmentStatus::where('department_id', $latest_enrollment->department_id)->get();
        	$enrollment_status_items = 	EnrollmentStatusItem::whereIn('enrollment_status_id', $enrollment_status->pluck('id'))
            								->where('term', '!=', 'Summer')
            								->where('term', $nextTerm)
            								->active()
            								->first();
           	if(!$enrollment_status_items) {
            	return null;
            }

            $nextEnrollment['schoolYear']           = $enrollment_status_items->enrollment_status->schoolYear;
            $nextEnrollment['department']           = $latest_enrollment->department;
            $nextEnrollment['level']                = $latest_enrollment->level;
            $nextEnrollment['curriculum']           = $latest_enrollment->curriculum;
            $nextEnrollment['term_type']            = $nextTerm;
            $nextEnrollment['track']                = $latest_enrollment->track ? TrackManagement::where([
                                                                                'code' => $latest_enrollment->track->code,
                                                                                'level_id' => $latest_enrollment->level->id
                                                                            ])->first() : null;

            if($nextEnrollment['department']->with_track && !$nextEnrollment['track'])
            {
                $departmentTracks = TrackManagement::where('level_id', $nextEnrollment['level'] ->id)->get();
            }

            return $nextEnrollment;
        }

        /*
        |--------------------------------------------------------------------------
        | FULLTERM TYPE NEXT ENROLLMENT
        |--------------------------------------------------------------------------
        */
        if($term->type == "FullTerm") {
        	if($latest_enrollment->term_type == "Full") {
        		$nextGradeLevel = YearManagement::where('sequence', $latest_enrollment->level->sequence + 1)->first();
        		$term_type 		= null;

        		if(!$nextGradeLevel) {
        			return null;
        		}

        		if($nextGradeLevel->department->term->type == "FullTerm") {
                    $term_type  = "Full";
                    $curriculum = $latest_enrollment->curriculum;
                    $track      = $latest_enrollment->track;
                }

                if($nextGradeLevel->department->term->type == "Semester") {
                    $term_type  = "First";
                    $curriculum = null;
                    $track      = null;
                }

                if(!$term_type) {
        			return null;
        		}

        		$enrollment_status 		 = 	EnrollmentStatus::where('department_id', $nextGradeLevel->department_id)
        										->where('school_year_id', '!=', $latest_enrollment->school_year_id)
        										->where('summer', 0)
        										->get();
            	$enrollment_status_items = 	EnrollmentStatusItem::whereIn('enrollment_status_id', $enrollment_status->pluck('id'))
	            								->where('term', $term_type)
	            								->active()
	            								->first();

	           	if(!$enrollment_status_items) {
                	return null;
                }

                $nextEnrollment['schoolYear']   = $enrollment_status_items->enrollment_status->schoolYear;
                $nextEnrollment['department']   = $nextGradeLevel->department;
                $nextEnrollment['level']        = $nextGradeLevel;
                $nextEnrollment['curriculum']   = $curriculum;
                $nextEnrollment['term_type']	= $term_type;
                $nextEnrollment['track']		= $track;

                if($nextEnrollment['department']->with_track && !$nextEnrollment['track']) {
                    $departmentTracks = TrackManagement::where('level_id', $nextEnrollment['level'] ->id)->get();
                    $nextEnrollment['department_tracks'] = $departmentTracks;
                }
        	}
        	return $nextEnrollment;
        }
        return $nextEnrollment;
    }

    public function getStudentSummerEnrollment($student_id)
    {
    	$student    = Student::where('id', $student_id)->first();

        if(! $student->studentnumber) {
            return null;
        }
        $active_school_year = 	SchoolYear::active()->first();
        $summer_enrollment  = 	Enrollment::where('studentnumber', $student->studentnumber)
        							->where('school_year_id', $active_school_year->id)
        							->where('term_type', 'Summer')
        							->latest()
        							->first();

        if($summer_enrollment) { 
            return null; 
        }

        $latest_enrollment  = 	Enrollment::where('studentnumber', $student->studentnumber)
        							->where('school_year_id', $active_school_year->id)
        							->where('term_type', '!=', 'Summer')
        							->latest()
        							->first();

        if(! $latest_enrollment) { 
            return null; 
        }

        $enrollment_status 		 = 	EnrollmentStatus::where('department_id', $latest_enrollment->department_id)
										->where('school_year_id', $latest_enrollment->school_year_id)
										->where('summer', 1)
										->get();

    	$enrollment_status_items = 	EnrollmentStatusItem::whereIn('enrollment_status_id', $enrollment_status->pluck('id'))
        								->where('term', 'Summer')
        								->active()
        								->first();

        if(!$enrollment_status_items) {
        	return null;
        }

        $summerEnrollment['schoolYear'] = $active_school_year;
        $summerEnrollment['department'] = $latest_enrollment->department;
        $summerEnrollment['level'] 		= $latest_enrollment->level;
        $summerEnrollment['curriculum'] = $latest_enrollment->curriculum;
        $summerEnrollment['term_type'] 	= 'Summer';
        $summerEnrollment['track'] 		= $latest_enrollment->track;

        return $summerEnrollment;
    }

    /**
     * Enroll
     * Other Program
     *
     */
    public function enrollOtherProgram(Request $request)
    {
        if(!config('settings.allow_program_and_service_enrollment')) {
            abort(404);
        }

        $student    = auth()->user()->student;
        if(! $student->studentnumber) {
            return null;
        }
        $active_sy     = SchoolYear::active()->first();
        $other_program = OtherProgram::where('id', $request->other_program_id)->first();

        if(! $other_program) {
            \Alert::warning("Program Not Found!")->flash();
            return redirect()->back();
        }

        $current_enrollment     =   Enrollment::where('studentnumber', $student->studentnumber)
                                        ->where('school_year_id', $active_sy->id)
                                        ->where('term_type', '!=', 'Summer')
                                        ->latest()
                                        ->first();

        if(! $current_enrollment) {
            \Alert::warning("Account not Enrolled!")->flash();
            return redirect()->back();
        }

        $selectedOtherProgram   =   SelectedOtherProgram::where('enrollment_id', $current_enrollment->id)
                                    ->where('other_program_id', $other_program->id)
                                    ->first();

        if($selectedOtherProgram) {
            \Alert::warning("Selected Program is already enrolled!")->flash();
            return redirect()->back();
        }

        $create_other_proggram  = SelectedOtherProgram::create([
            'enrollment_id'     => $current_enrollment->id,
            'other_program_id'  => $other_program->id,
            'added_by'          => 'Student',
            'approved'          => 0
        ]);

        if($create_other_proggram) {
            \Alert::success("Selected Program has been enrolled successfuly!")->flash();
            return redirect()->back();
        } else {
            \Alert::error("Unable to enroll, <br> Something went wrong, please try to reload the page.")->flash();
            return redirect()->back();
        }
    }

    /**
     * Enroll
     * Other Service
     *
     */
    public function enrollOtherService(Request $request)
    {
        if(!config('settings.allow_program_and_service_enrollment')) {
            abort(404);
        }
        
        $student    = auth()->user()->student;
        if(! $student->studentnumber) {
            return null;
        }
        $active_sy     = SchoolYear::active()->first();
        $other_service = OtherService::where('id', $request->other_service_id)->first();

        if(! $other_service) {
            \Alert::warning("Service Not Found!")->flash();
            return redirect()->back();
        }

        $current_enrollment   = Enrollment::where('studentnumber', $student->studentnumber)
                                    ->where('school_year_id', $active_sy->id)
                                    ->where('term_type', '!=', 'Summer')
                                    ->latest()
                                    ->first();

        if(! $current_enrollment) {
            \Alert::warning("Account not Enrolled!")->flash();
            return redirect()->back();
        }

        $selectedOtherService   =   SelectedOtherService::where('enrollment_id', $current_enrollment->id)
                                    ->where('other_service_id', $other_service->id)
                                    ->first();

        if($selectedOtherService) {
            \Alert::warning("Selected Service is already enrolled!")->flash();
            return redirect()->back();
        }
        
        $create_other_service  = SelectedOtherService::create([
            'enrollment_id'     => $current_enrollment->id,
            'other_service_id'  => $other_service->id,
            'added_by'          => 'Student',
            'approved'          => 0
        ]);

        if($create_other_service) {
            \Alert::success("Selected Service has been enrolled successfuly!")->flash();
            return redirect()->back();
        } else {
            \Alert::error("Unable to enroll, <br> Something went wrong, please try to reload the page.")->flash();
            return redirect()->back();
        }
    }
}
