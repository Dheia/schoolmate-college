<?php

namespace App\Http\Controllers\Kiosk;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

// MODELS
use App\Models\Department;
use App\Models\SchoolYear;
use App\Models\YearManagement;
use App\Models\TrackManagement;
use App\Models\Enrollment;
use App\Models\Tuition;
use App\Models\CommitmentPayment;
use App\Models\Student;
use App\Models\TermManagement;
use App\Models\KioskEnrollment;
use App\Models\KioskSetting;
use App\Models\Requirement;
use App\Models\EnrollmentStatus;
use App\Models\EnrollmentStatusItem;
use App\Models\Referral;

use Validator;
use Carbon\Carbon;

use App\Jobs\MailNewStudentJob;
use App\Mail\SendMailableKioskAfterEnrolled;
use App\Mail\SendMailableKioskNewStudent;
use Mail;
use Log;

use App\Http\Controllers\Admin\SmartJwtCredentialCrudController;

class EnlistingController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $schoolYearActive = SchoolYear::active()->first();
        $oldStudentOption = KioskSetting::where('key', 'old_student_option')->first();
        $newStudentOption = KioskSetting::where('key', 'new_student_option')->first();
        $announcement     = KioskSetting::where('key', 'announcement')->first();
        // return view('kiosk.home');
        return view('kiosk.new_home', compact('schoolYearActive', 'oldStudentOption', 'newStudentOption', 'announcement'));
    }

    private function getNextStudentUserId ($schoolYear) {
        
        $extracted     = preg_replace('/-(\d+)/', '', $schoolYear);
        $lastTwoDigits = substr($extracted, -2); 

        $studentId = Student::where('studentnumber', 'LIKE', (int)$lastTwoDigits . '%')
                               // ->orWhere('deleted_at', 'null')
                               ->orderBy('studentnumber', 'DESC')
                               ->pluck('studentnumber')
                               ->first();
                               // dd($studentId);
        if($studentId == null) {
            $studentId = $lastTwoDigits . "0001";
            return (int)$studentId;
        }                       

        return (int)$studentId + 1;
    }

    public function newStudentStore (Request $request)
    {
        $newSchoolOption = KioskSetting::where('key', 'new_school_option')->first();
        $initialPayment  = KioskSetting::where('key', 'initial_payment')->first();
        $termsConditions = KioskSetting::where('key', 'terms_conditions')->first();
        $allowReferral   = KioskSetting::where('key', 'referral')->first();

        $rules = [

            'schoolyear'                    => 'required|numeric',
            'level_id'                      => 'required|numeric',
            // 'track_id'                      => 'nullable|numeric',
            // 'track_description'             => 'string',

            /**
             * STUDENT INFORMATION
             */
            'lastname'                      => 'required|max:100',
            'firstname'                     => 'required|max:100',
            'middlename'                    => 'max:20',
            'gender'                        => 'required|in:Male,Female',
            'citizenship'                   => 'string|max:100',
            'religion'                      => 'required|string|max:100',
            'birthdate'                     => 'required|date',
            'birthplace'                    => 'required|string|max:100',
            // 'age'                           => 'required'
            // 'residentialaddress'            => 'required|max:100',
            'street_number'                 => 'required|string',
            'barangay'                      => 'required|string',
            'city_municipality'             => 'required|string',
            'province'                      => 'required|string',
            'contactnumber'                 => 'numeric|nullable',

            'email'                         => 'nullable|email',
            'is_transferee'                 => 'required|boolean',

            
            'lrn'                           => 'numeric|nullable',

            /**
             * FATHER'S INFORMATION
             */
            'fatherlastname'                => 'required|string|max:100',
            'fatherfirstname'               => 'required|string|max:100',
            'fathermiddlename'              => 'max:100',
            // 'fatherCitizenship'             => 'string',
            'father_occupation'             => 'required|string',
            'fatherMobileNumber'            => 'numeric|nullable',
            // 'father_living_deceased'        => 'required|in:living,deceased',
            
            /**
             * MOTHER'S INFORMATION
             */
            'motherlastname'                => 'required|string|max:100',
            'motherfirstname'               => 'required|string|max:100',
            'mothermiddlename'              => 'max:100',
            // 'motherCitizenship'             => 'string',
            'mother_occupation'             => 'required|string',
            'mothernumber'                  => 'numeric|nullable',
            // 'mother_living_deceased'        => 'required|in:living,deceased',

            /**
             * EMERGENCY CONTACT INFORMATION
             */
            'emergencyRelationshipToChild'  => 'required|in:Father,Mother,LegalGuardian,Other',
            // 'emergencyaddress'              => 'required|string',
            // 'emergencymobilenumber'         => 'required|numeric',
            'emergencyhomephone'            => 'required|numeric', 

            /**
             * LEGAL GUARDUIAN'S INFORMATION
             */
            // 'legal_guardian_lastname'       => 'required|string',
            // 'legal_guardian_firstname'      => 'required|string',
            // // 'legal_guardian_middlename'     => 'string',
            // // 'legal_guardian_citizenship'    => 'string',
            // 'legal_guardian_occupation'     => 'required|string',
            // 'legal_guardian_contact_number' => 'numeric',

            // LIVING
            // 'living'                        => 'required',
            'email_input'                   => 'required|email'
        ];

        if($newSchoolOption)
        {
            if($newSchoolOption->active){
                 $rules['old_or_new']    = ['required', Rule::in(['old', 'new'])];
                // $rules['inclusive_dates']   = 'required';
            }
        }

        if($allowReferral) {
            if($allowReferral->active) {
                $rules['referral_type']     = 'required';
                $rules['referred_by']       = 'required';
                // $rules['referrer_contact']  = 'required_if:referral_type,referred';
                $rules['referrer_contact']  = 'nullable';
            }
        }

        if($termsConditions)
        {
            if($termsConditions->active){
                $rules['terms_conditions']    = 'accepted';
            }
        }

        if((int)$request->is_transferee)
        {
            $rules['previousschool']    = 'required|string|max:100';
            // $rules['inclusive_dates']   = 'required';
        }

        /**
         * IF OTHER SELECTED MUST BE REQUIRED THIS FIELDS
         */
        if($request->emergencyRelationshipToChild === 'Other')
        {
            $rules['emergency_contact_other_relation_ship_to_child']    = 'required|string';
            $rules['emergency_lastname']                                = 'required|string';
            $rules['emergency_firstname']                               = 'required|string';
            $rules['emergency_middlename']                              = 'nullable|string';
            // $rules['emergency_citizenship']                             = 'required|string';
        }

        /**
         * IF LEGAL GUARDIAN SELECTED MUST BE REQUIRED THAT FIELDS
         */
        if($request->emergencyRelationshipToChild === 'LegalGuardian')
        {
            $rules['legal_guardian_lastname']        = 'required|string';
            $rules['legal_guardian_firstname']       = 'required|string';
            $rules['legal_guardian_occupation']      = 'required|string';
            $rules['legal_guardian_contact_number']  = 'required|numeric';
        }
        else
        {
            $rules['legal_guardian_lastname']        = 'nullable|string';
            $rules['legal_guardian_firstname']       = 'nullable|string';
            $rules['legal_guardian_occupation']      = 'nullable|string';
            $rules['legal_guardian_contact_number']  = 'nullable|numeric';

        }

        // VALIDATE TERM TYPE BASED ON NUMBER OF TERMS PROVIDED
        // $term = TermManagement::where('department_id', $request->department_id)->first();
        // if($term) {
        //     $rules['term'] = ['required', Rule::in($term->ordinal_terms)];
        // } else {
        //     \Alert::warning("No Terms Found, Please Make Sure The Term Management Has Value")->flash();
        //     return redirect()->back()->withInput();
        // }

        $living = [ 
            "father"         => 0,
            "step-father"    => 0,
            "mother"         => 0,
            "step-mother"    => 0,
            "legal_guardian" => 0,
            'other-relative' => 0,
        ];

        if($request->living != null) {
            foreach ($request->living as $lv) {
                if(array_key_exists($lv, $living)) {
                    $living[$lv] = 1;
                }
            }
        }

        /**
         * CHECK IF LIVING OTHER RELATIVE IS CHECKED
         */
        if($living['other-relative'] == 1) {
            $rules['other_relative'] = 'required|string';
        }

         // MAIN VALIDATOR
        $validator = Validator::make($request->all(), $rules);

        // CHECK IF THE SCHOOL YEAR ID IS ACTIVE 
        $schoolYearActive = SchoolYear::where('id', $request->schoolyear)->active()->first();
        $item_id = \Route::current()->parameter('item_id');
        $enrollmentStatusItem = EnrollmentStatusItem::with('enrollment_status')->where('id', $item_id)->first();
        
        abort_if(! $enrollmentStatusItem, 404, 'Enrollment Status Not Found.');
        abort_if(! $enrollmentStatusItem->active, 403, 'There is no open enrollment for this department.');
        
        /**
         * Validate SchoolYear Based On Enrollment Status
         */
        if($enrollmentStatusItem->enrollment_status->school_year_id != $request->schoolyear)
        {
            $validator->errors()->add('schoolyear"', 'Invalid School Year');

            $schoolYears = SchoolYear::active()->get(); 
            $levels      = YearManagement::all(); 
            $tracks      = TrackManagement::all();

            return redirect()->back()
                    ->withSchoolYears($schoolYears)
                    ->withLevels($levels)
                    ->withTracks($tracks)
                    ->withErrors($validator)
                    ->withInput($request->input());
        }

        /**
         * Validate Department Based On Enrollment Status
         */
        if($enrollmentStatusItem->enrollment_status->department_id != $request->department_id)
        {
            $validator->errors()->add('department_id"', 'Invalid Department');

            $schoolYears = SchoolYear::active()->get(); 
            $levels      = YearManagement::all(); 
            $tracks      = TrackManagement::all();

            return redirect()->back()
                    ->withSchoolYears($schoolYears)
                    ->withLevels($levels)
                    ->withTracks($tracks)
                    ->withErrors($validator)
                    ->withInput($request->input());
        }

        /**
         * Validate Term Based On Enrollment Status
         */
        if($enrollmentStatusItem->term != $request->term)
        {
            $validator->errors()->add('term"', 'Invalid Term');

            $schoolYears = SchoolYear::active()->get(); 
            $levels      = YearManagement::all(); 
            $tracks      = TrackManagement::all();

            return redirect()->back()
                    ->withSchoolYears($schoolYears)
                    ->withLevels($levels)
                    ->withTracks($tracks)
                    ->withErrors($validator)
                    ->withInput($request->input());
        }
        /**
         * GET DEPARTMENT, LEVEL, AND TRACK IF THEY ARE ENTITY CONNECTED
         */
        $depLevTrack = Department::where('id', $request->department_id)
                                ->with(['levels' => function ($q) { $q->with('tracks:id,code,level_id'); }])
                                ->first();

        /**
         * CHECK TRACK IS EMPTY OR NOT
         */
        $isLevelEmpty = collect($depLevTrack->levels)->whereIn('id', $request->level_id);
        if(count($isLevelEmpty) === 0)
        {
            $validator->errors()->add('level_id"', 'Invalid Level');

            $schoolYears = SchoolYear::active()->get(); 
            $levels      = YearManagement::all(); 
            $tracks      = TrackManagement::all();

            return redirect()->back()
                    ->withSchoolYears($schoolYears)
                    ->withLevels($levels)
                    ->withTracks($tracks)
                    ->withErrors($validator)
                    ->withInput($request->input());
        }

        $schoolYear = SchoolYear::where('id', $request->schoolyear)->pluck('schoolYear'); 

        if(count($schoolYear) == 0) 
        {
            \Alert::warning('Invalid School Year');
            return redirect()->back();
        }

        if ($validator->fails()) 
        {
            $schoolYears = SchoolYear::active()->get(); 
            $levels      = YearManagement::all(); 
            $tracks      = TrackManagement::all(); 

            return redirect()->back()
                    ->withSchoolYears($schoolYears)
                    ->withLevels($levels)
                    ->withTracks($tracks)
                    ->withErrors($validator)
                    ->withInput($request->input());
        }


        // IF NO ERRORS 
        $student                                = new Student;
        // $student->studentnumber                 = self::getNextStudentUserId($schoolYear[0]);
        $student->application                   = \Carbon\Carbon::now();
        // $student->lrn                            = $request->lrn;
        $student->schoolyear                    = $request->schoolyear;
        $student->level_id                      = $request->level_id;
        $student->department_id                 = $request->department_id;
        $student->track_id                      = $request->track_id;
        
        $student->new_or_old                    = "new";
        $student->living                        = json_encode($living, true);
        $student->other_relative                = $request->other_relative;

        // STUDENT
        $student->lastname                      = $request->lastname;
        $student->firstname                     = $request->firstname;
        $student->middlename                    = $request->middlename;
        $student->gender                        = $request->gender;
        $student->citizenship                   = $request->citizenship;
        $student->religion                      = $request->religion;
        $student->birthdate                     = $request->birthdate;
        $student->age                           = Carbon::parse($request->birthdate)->age;
        $student->birthplace                    = $request->birthplace;
        // $student->residentialaddress            = $request->residentialaddress;
        $student->street_number                 = $request->street_number;
        $student->barangay                      = $request->barangay;
        $student->city_municipality             = $request->city_municipality;
        $student->province                      = $request->province;
        $student->contactnumber                 = $request->contactnumber;
        $student->email                         = $request->email;
        $student->is_transferee                 = $request->is_transferee;
        $student->previousschool                = $request->previousschool;

        // Father's
        $student->fatherlastname                = $request->fatherlastname;
        $student->fatherfirstname               = $request->fatherfirstname;
        $student->fathermiddlename              = $request->fathermiddlename;
        $student->fatherCitizenship             = $request->fatherCitizenship;
        $student->father_occupation             = $request->father_occupation;
        $student->fatherMobileNumber            = $request->fatherMobileNumber;
        $student->father_living_deceased        = $request->father_living_deceased ?? 'living';

        // Mother's
        $student->motherlastname                = $request->motherlastname;
        $student->motherfirstname               = $request->motherfirstname;
        $student->mothermiddlename              = $request->mothermiddlename;
        $student->motherCitizenship             = $request->motherCitizenship;
        $student->mother_occupation             = $request->mother_occupation;
        $student->mothernumber                  = $request->mothernumber;
        $student->mother_living_deceased        = $request->mother_living_deceased ?? 'living';
        
        // LEGAL GUARDIAN
        $student->legal_guardian_lastname       = $request->legal_guardian_lastname;
        $student->legal_guardian_firstname      = $request->legal_guardian_firstname;
        $student->legal_guardian_middlename     = $request->legal_guardian_middlename;
        $student->legal_guardian_citizenship    = $request->legal_guardian_citizenship;
        $student->legal_guardian_occupation     = $request->legal_guardian_occupation;
        $student->legal_guardian_contact_number = $request->legal_guardian_contact_number;

        // Emergency
        $student->emergencycontactname          = $request->emergencycontactname;
        $student->emergencyRelationshipToChild  = $request->emergencyRelationshipToChild;
        $student->emergency_contact_other_relation_ship_to_child = $request->emergency_contact_other_relation_ship_to_child;        
        $student->emergencymobilenumber         = $request->emergencymobilenumber;
        $student->emergencyaddress              = $request->emergencyaddress;
        $student->emergencyhomephone            = $request->emergencyhomephone;
        
        // Tranferees
        $student->is_transferee                 = $request->is_transferee;
        // $student->inclusive_dates           = $request->inclusive_dates;
        // 
        if($student->save())
        {
            // ENROLLMENT
            $enrollment                 = new Enrollment;
            $enrollment->student_id     = $student->id;
            $enrollment->school_year_id = $request->schoolyear;
            $enrollment->department_id  = $request->department_id;
            $enrollment->level_id       = $request->level_id;
            $enrollment->track_id       = $request->track_id;
            $enrollment->term_type      = $request->term;
            $enrollment->is_applicant   = 1;

            if($newSchoolOption->active)
            {
                $enrollment->old_or_new     = $request->old_or_new;
            }
            else
            {
                $enrollment->old_or_new = 'new';
            }
            $enrollment->require_payment = $initialPayment ? $initialPayment->active : '0';
            if($initialPayment)
            {
                if($initialPayment->active)
                {
                    // if a new file is uploaded, store it on disk and its filename in the database
                    if ($request->hasFile('proof_of_payment')) {
                        $attribute_value = [];
                        $disk = "public";
                        $destination_path = "uploads/students/requirements/proof_of_payment";
                        foreach ($request->file('proof_of_payment') as $file) {
                            if ($file->isValid()) {
                                // 1. Generate a new file name
                                $new_file_name = $file->getClientOriginalName();
                                // 2. Move the new file to the correct path
                                $file_path = $file->storeAs($destination_path, $new_file_name, $disk);
                                // 3. Add the public path to the database
                                $attribute_value[] = $file_path;
                            }
                        }
                        $enrollment->proof_of_payment = $attribute_value;
                    }
                }
            }


            $enrollment->save();

            // Referral
            if($allowReferral) {
                if($allowReferral->active) {
                    $referral = Referral::create([
                        'student_id'  => $student->id,
                        'medium'      => $request->referral_type,
                        'referred_by' => $request->referred_by,
                        'contact' => $request->referrer_contact,
                    ]);
                }
            }

            // KIOSK ENROLLMENT
            $kioskEnrollment                 = new KioskEnrollment;
            $kioskEnrollment->kiosk_id       = uniqid();
            $kioskEnrollment->student_id     = $student->id;
            $kioskEnrollment->enrollment_id  = $enrollment->id;
            $kioskEnrollment->student_status = 'new';
            $kioskEnrollment->email          = $request->email_input;
            $kioskEnrollment->save();

            // Mail::to($request->email_input)->send(new SendMailableKioskNewStudent($kioskEnrollment));
            MailNewStudentJob::dispatch($kioskEnrollment);


            $smartJWT = new SmartJwtCredentialCrudController;

            $smartRequest = new Request;
            $smartRequest->request->add([
                'subscriber_number' => [$request->emergencyhomephone],
                'message' => 'Congratulations! Your application at ' . config('settings.schoolname') . ' is now being processed. Kindly check this email ' . $request->email_input . ' and print the form that has been sent.',
                'log_id' => uniqid()
            ]);

            $resp = $smartJWT->sendSms($smartRequest);
            $resp = json_encode($resp);

            \Session::flash("message", "You have been successfully enlisted, please check your email.");
            
            $additionalPage = KioskSetting::where('key', 'additional_page')->first();
            if($additionalPage)
            {
                if($additionalPage->active)
                {
                    return view('kiosk/after_submission')->with('additionalPage', $additionalPage);
                }
                else{
                    return redirect()->to('kiosk/enlisting');
                }
            }
            else{
                return redirect()->to('kiosk/enlisting');
            }
        }
        else
        {
            return redirect()->back();
        }
    }

    public function displayAccordingStudentType ($type, $item_id = null, $studentnumber = null, Request $request)
    {
        $searchTerm = $request->studentnumber;
        $nextGradeLevel = null;
        $oldStudentOption = KioskSetting::where('key', 'old_student_option')->first();
        $newStudentOption = KioskSetting::where('key', 'new_student_option')->first();
        $termsConditions  = KioskSetting::where('key', 'terms_conditions')->first();
        $allowReferral    = KioskSetting::where('key', 'referral')->first();
        
        /*********************/
        //--- NEW STUDENT ---//
        /*********************/
        if($type === 'new')
        {
            $type = 'new';
            if(!$newStudentOption->active) { abort(403); }
            $schoolYearActive = SchoolYear::active()->first();
            $schoolYears      = SchoolYear::active()->get(); 
            $levels           = YearManagement::all(); 
            $tracks           = TrackManagement::all(); 
            $departments      = Department::active()->select('id', 'name')->get(); 
            $newSchoolOption  = KioskSetting::where('key', 'new_school_option')->first();
            $initialPayment   = KioskSetting::where('key', 'initial_payment')->first();

            $enrollmentStatusItems = EnrollmentStatusItem::with('enrollment_status')->get();

            // NEW STUDENT SELECTED ENROLLMENT STATUS ITEM
            if($item_id)
            {
                $enrollmentStatus  =  EnrollmentStatusItem::with('enrollment_status')->where('id', $item_id)->first();
                if(!$enrollmentStatus) { abort(404, 'Enrollment Status Not Found.'); }
                if(!$enrollmentStatus->active) { abort(403, 'Enrollment for this Department is not open'); }
                return view('kiosk.new_create', compact('schoolYears', 'levels', 'tracks', 'departments', 'schoolYearActive', 'newSchoolOption', 'initialPayment', 'enrollmentStatus', 'termsConditions', 'allowReferral'));
            }
            return view('kiosk.newStudent.departments', compact('schoolYears', 'levels', 'tracks', 'departments', 'enrollmentStatusItems', 'type'));
            // return view('kiosk.new_create', compact('schoolYears', 'levels', 'tracks', 'departments', 'schoolYearActive', 'newSchoolOption', 'initialPayment'));
        }

        /*********************/
        //--- OLD STUDENT ---//
        /*********************/
        if($type === 'old' && $request->getMethod() == 'GET' && $studentnumber === null && !$request->has('studentnumber'))
        {
            $enrollmentStatusItem = EnrollmentStatusItem::with('enrollment_status')->where('id', $item_id)->first();
            $type = 'old';
            if(!$oldStudentOption->active) { abort(403); }

            $schoolYears      = SchoolYear::all(); 
            $schoolYearActive = SchoolYear::active()->first();
            $levels           = YearManagement::all(); 

            if($schoolYearActive == null) { return redirect()->back(); }

            $enrollmentStatusItems = EnrollmentStatusItem::with('enrollment_status')->get();

            // NEW STUDENT SELECTED ENROLLMENT STATUS ITEM
            if($item_id)
            {
                $enrollmentStatus  =  EnrollmentStatusItem::with('enrollment_status')->where('id', $item_id)->first();
                if(!$enrollmentStatus) { abort(403); }
                if(!$enrollmentStatus->active) { abort(403); }
                return view ('kiosk.old.newSearchStudent', compact('schoolYears', 'levels', 'schoolYearActive', 'enrollmentStatusItem'));
            }
            else{
                return view('kiosk.newStudent.departments', compact('schoolYears', 'levels', 'enrollmentStatusItems', 'type'));
            }
            // return view ('kiosk.old.searchStudent', compact('schoolYears', 'levels', 'schoolYearActive'));
            return view ('kiosk.old.newSearchStudent', compact('schoolYears', 'levels', 'schoolYearActive', 'enrollmentStatusItem'));
        }

        // ENROLLING STUDENT
        if($type === 'old' && $request->getMethod() == 'POST' && $studentnumber !== null && !$request->has('studentnumber'))
        {
            $enrollmentStatusItem = EnrollmentStatusItem::with('enrollment_status', 'enrollment_status.schoolYear')->where('id', $item_id)->first();
            if(!$enrollmentStatusItem) { abort(403, 'Enrollment Not Found'); }
            if(!$enrollmentStatusItem->active) { abort(403,  'Enrollment Not Found'); }

            $oldVal = $studentnumber;
            $departmentTracks   = null;
            // SELECTED NEXT GRADE LEVEL 

            if(!$oldStudentOption->active) { abort(403); }
            $schoolYearActive = SchoolYear::active()->first();
            $student          = Student::where('studentnumber', $studentnumber)->with(['yearManagement'])->first();

            $enrollment       = Enrollment::where('studentnumber', $studentnumber)
                                    ->with(['student', 'tuition', 'schoolYear', 'department', 'level', 'track', 'curriculum', 'commitmentPayment'])
                                    ->where('term_type', '!=', 'Summer')
                                    ->latest()
                                    ->first();

            if($enrollment == null) {
                abort(404, "Student Has No Previous Record Enrollments");
            }

            // Check If Enrollment Is Already Enrolled 
            if($enrollment->term_type == "Full" && $enrollment->school_year_id === $enrollmentStatusItem->enrollment_status->school_year_id) {
                abort(403, "You are Already Enrolled In This Current School Year Of <br> <b>" . $enrollmentStatusItem->enrollment_status->school_year_name . "</b>");
            }

            // GET TERM TYPE
            $term = TermManagement::where('department_id', $enrollment->department_id)->first();
            
            // GET THE NEXT GRADE LEVEL
            $nxtGradeLevel = [];

            if($enrollment !== null) {
                // If Term Type Is Full
                if($term->type === "FullTerm") {

                    // GET THE NEXT ELIGIBLE ENROLLMENT
                    if($enrollment->term_type == "Full") {
                        $grade_level = YearManagement::with('department', 'department.term')->where('sequence', $enrollment->level->sequence + 1)->first();
                        if($grade_level !== null) {
                            $nxtGradeLevel['grade_level'] = $grade_level->year;

                            $nxtGradeLevel['schoolYear']           = $enrollmentStatusItem->enrollment_status->schoolYear;
                            $nxtGradeLevel['department']           = $grade_level->department;
                            $nxtGradeLevel['level']                = $grade_level;
                            $nxtGradeLevel['allow']                = 1;
                            if($enrollment->department_id == $grade_level->department_id)
                            {
                                $nxtGradeLevel['curriculum']           = $enrollment->curriculum_id;
                                $nxtGradeLevel['commitmentPayment']    = null;
                            }
                            else{
                                $nxtGradeLevel['curriculum']           = null;
                                $nxtGradeLevel['commitmentPayment']    = null;
                            }

                            if($grade_level->department->term->type == "FullTerm")
                            {
                                $nxtGradeLevel['term_type']            = "Full";
                                if($enrollment->school_year_id == $enrollmentStatusItem->enrollment_status->school_year_id) 
                                {
                                    abort(403, "You are Already Enrolled In This Current School Year Of <br> <b>" . $enrollmentStatusItem->enrollment_status->school_year_name . "</b>");
                                }
                            }

                            if($grade_level->department->term->type == "Semester")
                            {
                                $nxtGradeLevel['term_type']            = "First";
                                if($enrollmentStatusItem->term != $nxtGradeLevel['term_type'])
                                {
                                    abort(403, "You are NOT Allowed to Enrolled In This Current School Year Of <br> <b>" . $enrollmentStatusItem->enrollment_status->school_year_name . "</b> - " . $enrollmentStatusItem . ' Term');
                                }
                            }


                        } else {
                            abort(403, "No Eligible Enrollment Found.");

                        }
                        if($enrollment->school_year_id == $enrollmentStatusItem->enrollment_status->school_year_id) 
                        {
                            abort(403, "You are Already Enrolled In This Current School Year Of <br> <b>" . $enrollmentStatusItem->enrollment_status->school_year_name . "</b> - " . $enrollmentStatusItem . ' Term');
                        }
                        if($nxtGradeLevel['term_type'] != $enrollmentStatusItem->term)
                        {
                            abort(403, "You are NOT Allowed to Enrolled In This Current School Year Of <br> <b>" . $enrollmentStatusItem->enrollment_status->school_year_name . "</b> - " . $enrollmentStatusItem . ' Term');
                        }
                    }
                }


                // Start Semester
                if($term->type == "Semester") {
                    // Check The Term If It Is `First` or `Second` Term
                    // FIRST
                    $previousTerm = ['index'=>null, 'term'=>null];
                    $maxTerm = ['index'=>null, 'term'=>null];
                    if(count($term->ordinal_terms)>0)
                    {
                        foreach ($term->ordinal_terms as $key => $value) {
                            if($value == $enrollment->term_type)
                            {
                                $previousTerm['index'] = $key;
                                $previousTerm['term'] = $value;
                            }
                            // GET LAST TERM OF THE TERM
                            $maxTerm['index'] = $key;
                            $maxTerm['term'] = $value;

                        }
                    }
                    $index = $previousTerm['index']+1;
                    if($maxTerm['index'] < $index)
                    {
                        // Get The Previous Level And Increment Level By Sequence, For The Next Grade Level
                        $previousGradeLevel = YearManagement::where('id', $enrollment->level->id)->first();
                        $grade_level        = YearManagement::where('sequence', $previousGradeLevel->sequence + 1)->first();

                        if(!$grade_level) {
                            return view ('kiosk.old.newSearchStudent', compact('schoolYearActive', 'student', 'enrollment', 'nextEnrollment', 'oldVal', 'enrollmentStatusItem', 'departmentTracks'));
                        }
                        $nxtGradeLevel['schoolYear']           = $enrollmentStatusItem->enrollment_status->schoolYear;
                        $nxtGradeLevel['department']           = $grade_level->department;
                        $nxtGradeLevel['level']                = $grade_level;
                        // $nextEnrollment['track']                = $enrollment->track;
                        $nxtGradeLevel['track']                = $enrollment->track ? TrackManagement::where([
                                                                                            'code' => $enrollment->track->code,
                                                                                            'level_id' => $grade_level->id
                                                                                        ])->first() : null;
                        $nxtGradeLevel['curriculum']           = $enrollment->curriculum;
                        $nxtGradeLevel['commitmentPayment']    = $enrollment->commitmentPayment;
                        $nxtGradeLevel['term_type']            = "First";
                        $nxtGradeLevel['allow']                = 1;

                        if($grade_level->department_id != $enrollmentStatusItem->enrollment_status->department_id)
                        {
                            $enrollment = null;
                        }
                    }
                    else
                    {
                        $nextTerm = array_values($term->ordinal_terms)[$index];

                        $nxtGradeLevel['schoolYear']           = $enrollmentStatusItem->enrollment_status->schoolYear;
                        $nxtGradeLevel['department']           = $enrollment->department;
                        $nxtGradeLevel['level']                = $enrollment->level;
                        $nxtGradeLevel['track']                = $enrollment->track ? TrackManagement::where([
                                                                                            'code' => $enrollment->track->code,
                                                                                            'level_id' => $enrollment->level->id
                                                                                        ])->first() : null;
                        $nxtGradeLevel['curriculum']           = $enrollment->curriculum;
                        $nxtGradeLevel['commitmentPayment']    = $enrollment->commitmentPayment;
                        $nxtGradeLevel['term_type']            = $nextTerm;
                        $nxtGradeLevel['track']                = $enrollment->track ? TrackManagement::where([
                                                                                            'code' => $enrollment->track->code,
                                                                                            'level_id' => $enrollment->level_id
                                                                                        ])->first() : null;

                        if($nxtGradeLevel['schoolYear']['id'] != $enrollmentStatusItem->enrollment_status->school_year_id)
                        {
                            abort(403, "You are NOT Allowed to Enrolled In This Current School Year Of <br> <b>" . $enrollmentStatusItem->enrollment_status->school_year_name . "</b> - " . $enrollmentStatusItem . ' Term');
                        }

                        if (
                            $nxtGradeLevel['schoolYear']['id']  != $enrollmentStatusItem->enrollment_status->school_year_id || 
                            $nxtGradeLevel['term_type']         != $enrollmentStatusItem->term || 
                            $nxtGradeLevel['department']['id']  != $enrollmentStatusItem->enrollment_status->department_id 
                        ) {
                            $nxtGradeLevel['allow'] = 0;
                            $nxtGradeLevel = null;
                            abort(403, "You are NOT Allowed to Enrolled In This Current School Year Of <br> <b>" . $enrollmentStatusItem->enrollment_status->school_year_name . "</b> - " . $enrollmentStatusItem . ' Term');
                        }
                        else{
                            $nxtGradeLevel['allow']            = 1;
                        }
                    }

                    if($nxtGradeLevel){
                        if($nxtGradeLevel['department']->with_track && !$nxtGradeLevel['track'])
                        {
                            $departmentTracks = TrackManagement::where('level_id', $grade_level->id)->get();
                        }
                    }
                }
                // End Semester

                // VALIDATE ENROLLMENT TRACK IF DEPARTMENT HAS TRACKS
                if($nxtGradeLevel['level']->department->with_track)
                {
                    if($enrollment->track_id == null)
                    {
                        $nxtGradeLevel['allow']    = 1;
                        $departmentTracks = TrackManagement::where('level_id', $nxtGradeLevel['level']->id)->get();
                        if(!$request->track)
                        {

                            return view ('kiosk.old.newSearchStudent', compact('schoolYearActive', 'student', 'enrollment', 'oldVal', 'enrollmentStatusItem', 'departmentTracks'))
                                ->with('nextEnrollment', $nxtGradeLevel)->with('track_error', 'Please Select Track.');
                        }
                        else
                        {
                            $track = TrackManagement::where('id', $request->track)->first();
                            if(!$track)
                            {
                                return view ('kiosk.old.newSearchStudent', compact('schoolYearActive', 'student', 'enrollment', 'oldVal', 'enrollmentStatusItem', 'departmentTracks'))
                                    ->with('nextEnrollment', $nxtGradeLevel)->with('track_error', 'Track Not Found.');
                            }
                            else
                            {
                                $nxtGradeLevel['track']  = $track;
                            }
                        }

                    }
                    else
                    {
                        $track  = $enrollment->track ? TrackManagement::where([
                                                        'code' => $enrollment->track->code,
                                                        'level_id' => $nxtGradeLevel['level']->id
                                                    ])->first() : null;
                        if(!$track)
                        {
                            $departmentTracks = TrackManagement::where('level_id', $nxtGradeLevel['level']->id)->get();
                            return view ('kiosk.old.newSearchStudent', compact('schoolYearActive', 'student', 'enrollment', 'oldVal', 'enrollmentStatusItem', 'departmentTracks'))
                                    ->with('nextEnrollment', $nxtGradeLevel)
                                    ->with('track_error', 'Previous Enrollment Track Not Found.');
                        }
                        else
                        {
                            $nxtGradeLevel['track']  = $track;
                        }
                    }
                }
                else
                {
                    $nxtGradeLevel['track']  = null;
                }
            } 


            // GET THE YEAR ACTIVE
            $schoolYearActive = SchoolYear::active()->first();

            // CHECK TRACK IF ISSET IF NOT RETURN BACK
            // if(!isset($nxtGradeLevel['track'])) {
            //     return redirect()->back()->withInput();
            // }

            // GET TUITION ACCORDING TO THE GRADE AND YEAR LEVEL
            $track_id = $nxtGradeLevel['track'] !== null ? $nxtGradeLevel['track']->id : null;
            $tuition = Tuition::where([
                            'schoolyear_id' => $enrollmentStatusItem->enrollment_status->school_year_id,
                            'grade_level_id' => $nxtGradeLevel['level']->id,
                            'track_id' => $track_id,
                            'active' => 1
                        ])
                        ->first();
                        // dd($tuition);
                                // ->toSql();
            // GET COMMITMENT PAYMENT
            $commitmentPayment = CommitmentPayment::get();

            // return view ('kiosk.old.kioskEnrolling', compact('student', 'enrollment', 'tuition', 'studentnumber', 'nxtGradeLevel', 'schoolYearActive', 'commitmentPayment'));
            return view ('kiosk.old.newKioskEnrolling', compact('student', 'enrollment', 'tuition', 'studentnumber', 'nxtGradeLevel', 'schoolYearActive', 'commitmentPayment', 'enrollmentStatusItem'));
        }

        // SEARCHING STUDENT
        if($type === 'old' && $request->getMethod() == 'POST' && $studentnumber === null && $request->has('studentnumber'))
        {
            if(!$oldStudentOption->active) { abort(403); }
            // GET THE YEAR ACTIVE
            $schoolYearActive   = SchoolYear::active()->first();
            $student            = Student::where('studentnumber', $request->studentnumber)->first();
            $nextEnrollment     = null;
            $oldVal             = $request->studentnumber;
            $departmentTracks   = null;

            $enrollmentStatusItem = EnrollmentStatusItem::with('enrollment_status', 'enrollment_status.schoolYear')->where('id', $item_id)->first();
            if(!$enrollmentStatusItem) { abort(403); }
            if(!$enrollmentStatusItem->active) { abort(403); }
            // CHECK IF STUDENT HAS LAST PREVIOUS ENROLLMENT
            $enrollment = Enrollment::where('studentnumber', $request->studentnumber)
                                    ->with(['student', 'tuition', 'schoolYear', 'department', 'level', 'track', 'curriculum', 'commitmentPayment'])
                                    ->where('term_type', '!=', 'Summer')
                                    ->latest()
                                    ->first();

            // If PREVIOUS ENROLLMENT NOT NULL
            if($enrollment !== null) {
                // Check The Term Type By Matching The Department & Level
                $term = TermManagement::where('department_id', $enrollment->department_id)->first();

                if($term->type == "Semester") {
                    // Check The Maximum Term And The Term Of Previous Enrollment
                    // FIRST
                    $previousTerm = ['index'=>null, 'term'=>null];
                    $maxTerm = ['index'=>null, 'term'=>null];
                    if(count($term->ordinal_terms)>0)
                    {
                        foreach ($term->ordinal_terms as $key => $value) {
                            if($value == $enrollment->term_type)
                            {
                                $previousTerm['index'] = $key;
                                $previousTerm['term'] = $value;
                            }
                            // GET LAST TERM OF THE TERM
                            $maxTerm['index'] = $key;
                            $maxTerm['term'] = $value;

                        }
                    }
                    $index = $previousTerm['index']+1;
                    if($maxTerm['index'] < $index)
                    {
                        // Get The Previous Level And Increment Level By Sequence, For The Next Grade Level
                        $previousGradeLevel = YearManagement::where('id', $enrollment->level->id)->first();
                        $nextGradeLevel     = YearManagement::where('sequence', $previousGradeLevel->sequence + 1)->first();

                        if(!$nextGradeLevel) {
                            return view ('kiosk.old.newSearchStudent', compact('schoolYearActive', 'student', 'enrollment', 'nextEnrollment', 'oldVal', 'enrollmentStatusItem', 'departmentTracks'));
                        }

                        $nextEnrollment['schoolYear']           = $enrollmentStatusItem->enrollment_status->schoolYear;
                        $nextEnrollment['department']           = $nextGradeLevel->department;
                        $nextEnrollment['level']                = $nextGradeLevel;
                        // $nextEnrollment['track']                = $enrollment->track;
                        $nextEnrollment['track']                = $enrollment->track ? TrackManagement::where([
                                                                                            'code' => $enrollment->track->code,
                                                                                            'level_id' => $nextGradeLevel->id
                                                                                        ])->first() : null;
                        $nextEnrollment['curriculum']           = $enrollment->curriculum;
                        $nextEnrollment['commitmentPayment']    = $enrollment->commitmentPayment;
                        $nextEnrollment['term_type']            = "First";
                        $nextEnrollment['allow']                = 1;

                        if($nextGradeLevel->department->with_track && $enrollment->track_id == null)
                        {
                            $departmentTracks = TrackManagement::where('level_id', $grade_level->id)->get();
                        }
                        else
                        {
                            $nextEnrollment['track'] = $enrollment->track_id;
                        }

                        if($nextEnrollment['schoolYear']->id == $enrollmentStatusItem->enrollment_status->school_year_id) 
                        {
                            if($nextEnrollment['department']->term_type == "Semester")
                            {
                                if($nextEnrollment['term_type'] != $enrollmentStatusItem->term)
                                {
                                    $nextEnrollment          = null;
                                    $departmentTracks        = null;
                                    $nextEnrollment['allow'] = 0;
                                }
                            }
                            else{
                                if($nextEnrollment['term_type'] != $enrollmentStatusItem->term)
                                {
                                    $nextEnrollment          = null;
                                    $departmentTracks        = null;
                                    $nextEnrollment['allow'] = 0;
                                }
                            }
                        }
                        else
                        {
                            if($nextEnrollment['term_type'] != $enrollmentStatusItem->term)
                            {
                                $nextEnrollment          = null;
                                $departmentTracks        = null;
                                $nextEnrollment['allow'] = 0;
                            }
                        }
                        if($nextGradeLevel->department_id != $enrollmentStatusItem->enrollment_status->department_id)
                        {
                            $enrollment = null;
                        }
                    }
                    else
                    {
                        $nextTerm = array_values($term->ordinal_terms)[$index];

                        $nextEnrollment['schoolYear']           = $enrollmentStatusItem->enrollment_status->schoolYear;
                        $nextEnrollment['department']           = $enrollment->department;
                        $nextEnrollment['level']                = $enrollment->level;
                        $nextEnrollment['track']                = $enrollment->track ? TrackManagement::where([
                                                                                            'code' => $enrollment->track->code,
                                                                                            'level_id' => $enrollment->level->id
                                                                                        ])->first() : null;
                        $nextEnrollment['curriculum']           = $enrollment->curriculum;
                        $nextEnrollment['commitmentPayment']    = $enrollment->commitmentPayment;
                        $nextEnrollment['term_type']            = $nextTerm;

                        if($nextEnrollment['department']->id != $enrollmentStatusItem->enrollment_status->department_id)
                        {
                            $enrollment = null;
                        }
                        if($nextEnrollment['department']->with_track && !$nextEnrollment['track'])
                        {
                            $departmentTracks = TrackManagement::where('level_id', $nextEnrollment['level'] ->id)->get();
                        }

                        if($nextEnrollment['schoolYear']['id'] != $enrollmentStatusItem->enrollment_status->school_year_id || $nextEnrollment['term_type'] != $enrollmentStatusItem->term || $nextEnrollment['department']['id'] != $enrollmentStatusItem->enrollment_status->department_id)
                        {                            $nextEnrollment['allow']            = 0;
                            $nextEnrollment = null;
                        }
                        else{
                            $nextEnrollment['allow']            = 1;
                        }
                    }
                }

                if($term->type == "FullTerm") {
                    // GET THE NEXT ELIGIBLE ENROLLMENT
                    if($enrollment->term_type == "Full") {
                        $grade_level = YearManagement::with('department', 'department.term')->where('sequence', $enrollment->level->sequence + 1)->first();
                        if($grade_level !== null) {
                            $nxtGradeLevel['grade_level'] = $grade_level->year;

                            $nextEnrollment['schoolYear']           = $enrollmentStatusItem->enrollment_status->schoolYear;
                            $nextEnrollment['department']           = $grade_level->department;
                            $nextEnrollment['level']                = $grade_level;
                            $nextEnrollment['curriculum']           = $enrollment->curriculum_id;
                            $nextEnrollment['commitmentPayment']    = null;

                            if($grade_level->department->term->type == "FullTerm")
                            {
                                $nextEnrollment['term_type']            = "Full";
                            }

                            if($grade_level->department->term->type == "Semester")
                            {
                                $nextEnrollment['term_type']            = "First";
                            }

                            if($enrollment->school_year_id == $enrollmentStatusItem->enrollment_status->school_year_id) 
                            {
                                $nextEnrollment['allow']  = 0;
                            }
                            else
                            {
                                if($enrollmentStatusItem->term == $nextEnrollment['term_type'])
                                {
                                    $nextEnrollment['allow']  = 1;
                                }
                                else
                                {
                                    $nextEnrollment['allow']  = 0;
                                }
                            }

                            if($grade_level->department->with_track)
                            {
                                if($enrollment->track_id == null)
                                {
                                    $departmentTracks = TrackManagement::where('level_id', $grade_level->id)->get();
                                }
                                else
                                {
                                    $nextEnrollment['track'] =  $enrollment->track ? TrackManagement::where([
                                                                                            'code' => $enrollment->track->code,
                                                                                            'level_id' => $grade_level->id
                                                                                        ])->first() : null;
                                    if(!$nextEnrollment['track'])
                                    {
                                        $departmentTracks = TrackManagement::where('level_id', $grade_level->id)->get();
                                    }
                                }
                                
                            }
                            else
                            {
                                $nextEnrollment['track'] =  null;

                            }
                            if($nextEnrollment['department']->id != $enrollmentStatusItem->enrollment_status->department_id)
                            {
                                $enrollment = null;
                            }

                        } else {
                            if($enrollment->department_id != $enrollmentStatusItem->enrollment_status->department_id)
                            {
                                $enrollment = null;
                            }
                            $nxtGradeLevel['grade_level'] = null;
                            $nextEnrollment['allow']  = 0;

                        }

                        if($nextEnrollment['schoolYear']->id == $enrollmentStatusItem->enrollment_status->school_year_id) 
                        {
                            if($nextEnrollment['department']->term_type == "Semester")
                            {
                                if($nextEnrollment['term_type'] != $enrollmentStatusItem->term)
                                {
                                    $nextEnrollment = null;
                                    $departmentTracks = null;
                                }
                            }
                            else{
                                if($enrollment)
                                {
                                    if($enrollment->school_year_id == $enrollmentStatusItem->enrollment_status->school_year_id)
                                    {
                                        $nextEnrollment = null;
                                        $departmentTracks = null;
                                    }
                                }
                            }
                        }
                        else
                        {
                            if($nextEnrollment['department']->term_type != "FullTerm")
                            {
                                $nextEnrollment = null;
                                $departmentTracks = null;
                            }
                        }
                    }
                }


            }
            // $student = null;
            // return view ('kiosk.old.searchStudent', compact('schoolYearActive', 'student', 'enrollment', 'nextEnrollment', 'oldVal'));
            return view ('kiosk.old.newSearchStudent', compact('schoolYearActive', 'student', 'enrollment', 'nextEnrollment', 'oldVal', 'enrollmentStatusItem', 'departmentTracks'));
        }

    }

    public function enrollmentSubmit (Request $request)
    {
        $validator =    \Validator::make($request->all(), [
                            "studentnumber"     => 'required|numeric|exists:students,studentnumber',
                            "tuition_id"        => 'nullable|required_if:show_tuition,true|numeric|exists:tuitions,id',
                            "grade_level_id"    => 'required|numeric|exists:year_managements,id',
                            "schoolyear_id"     => 'required|numeric|exists:school_years,id',
                            "payment_id"        => 'required|numeric|exists:commitment_payments,id',
                            "curriculum_id"     => 'nullable|numeric|exists:curriculum_managements,id',
                            "email"             => 'required|email',
                            "track_id"          => 'nullable',
                            'enrollment_status_item' => 'required',
                            "show_tuition"      =>  'required'
                        ]);
        // VALIDATION
        if($validator->fails())
        {
            return response()->json(["status" => "ERROR", "message" => "Error Enrolling Student", "data" => $validator->errors()]);
        }
        $schoolYearActive = SchoolYear::active()->first();


        // ---------------------------------------
        // CHECK IF NEXT GRADE LEVEL IS CORRECT
        // ---------------------------------------
        // if(!$oldStudentOption->active) { abort(403); }
        // GET THE YEAR ACTIVE
        $schoolYearActive = SchoolYear::active()->first();
        $student          = Student::where('studentnumber', $request->studentnumber)->with(['yearManagement'])->first();
        $enrollment       = Enrollment::where('studentnumber', $request->studentnumber)->with(['schoolYear' , 'level', 'track', 'tuition'])
                                ->where('term_type', '!=', 'Summer')
                                ->latest()
                                ->first();

        // GET ENROLLMENT STATUS ITEM
        $enrollmentStatusItem = EnrollmentStatusItem::with('enrollment_status', 'enrollment_status.schoolYear')
                                    ->where('id', $request->enrollment_status_item)
                                    ->first();
        if(!$enrollmentStatusItem) { 
            return response()->json(["status" => "ERROR", "message" => "Enrollment Not Found", "data" => []]);
        }
        if(!$enrollmentStatusItem->active) {
            return response()->json(["status" => "ERROR", "message" => "Enrollment is Not Active", "data" => []]); 
        }

        if($student == null) {
            return response()->json(["status" => "ERROR", "message" => "Student Has No Previous Record Enrollments", "data" => []]);
        }

        if($enrollment == null) {
            return response()->json(["status" => "ERROR", "message" => "Student Has No Previous Record Enrollments", "data" => []]);
        }

        // Check If Enrollment Is Already Enrolled 
        if($enrollment->term_type == "Full" && $enrollment->school_year_id === $enrollmentStatusItem->enrollment_status->school_year_id) {
            return response()->json(["status" => "ERROR", "message" => "You are Already Enrolled In This Current School Year Of <br> <b>" . $enrollmentStatusItem->enrollment_status->school_year_name . "</b>", "data" => []]);
        }

        // GET ENROLLMENT STATUS ITEM
        $enrollmentStatusItem = EnrollmentStatusItem::with('enrollment_status', 'enrollment_status.schoolYear')
                                    ->where('id', $request->enrollment_status_item)
                                    ->first();
        if(!$enrollmentStatusItem) { 
            return response()->json(["status" => "ERROR", "message" => "Enrollment Not Found", "data" => []]);
        }
        if(!$enrollmentStatusItem->active) {  
            return response()->json(["status" => "ERROR", "message" => "Enrollment is Not Active", "data" => []]); 
        }
        // GET TERM TYPE
        $term = TermManagement::where('department_id', $enrollment->department_id)->first();
        
        // GET THE NEXT GRADE LEVEL
        $nxtGradeLevel = [];

        if($enrollment !== null) {
            // If Term Type Is Full
            if($term->type === "FullTerm") {

                if($enrollment->term_type == "Full") {
                    $grade_level = YearManagement::with('department', 'department.term')->where('sequence', $enrollment->level->sequence + 1)->first();
                    if($grade_level !== null) {
                        $nxtGradeLevel['grade_level'] = $grade_level->year;

                        $nxtGradeLevel['schoolYear']           = $enrollmentStatusItem->enrollment_status->schoolYear;
                        $nxtGradeLevel['department']           = $grade_level->department;
                        $nxtGradeLevel['level']                = $grade_level;
                        if($enrollment->department_id == $grade_level->department_id)
                        {
                            $nxtGradeLevel['curriculum']           = $enrollment->curriculum_id;
                            $nxtGradeLevel['commitmentPayment']    = null;
                        }
                        else{
                            $nxtGradeLevel['curriculum']           = null;
                            $nxtGradeLevel['commitmentPayment']    = null;
                        }

                        if($grade_level->department->term->type == "FullTerm")
                        {
                            $nxtGradeLevel['term_type']            = "Full";
                        }

                        if($grade_level->department->term->type == "Semester")
                        {
                            $nxtGradeLevel['term_type']            = "First";
                        }

                        if($enrollment->school_year_id == $enrollmentStatusItem->enrollment_status->school_year_id) 
                        {
                            return response()->json(["status" => "ERROR", "message" => "You are Already Enrolled In This Current School Year Of <br> <b>" . $enrollmentStatusItem->enrollment_status->school_year_name . "</b>", "data" => []]);
                        }
                        else
                        {
                            if($enrollmentStatusItem->term != $nxtGradeLevel['term_type'])
                            {
                                return response()->json(["status" => "ERROR", "message" => "You are Already Enrolled In This Current School Year Of <br> <b>" . $enrollmentStatusItem->enrollment_status->school_year_name . "</b> - " . $enrollmentStatusItem . " Term", "data" => []]);
                            }
                        }

                    } 
                    else {
                        return response()->json(["status" => "ERROR", "message" => "No Eligible Enrollment Found.", "data" => []]);

                    }
                    if($enrollment->school_year_id == $enrollmentStatusItem->enrollment_status->school_year_id) 
                    {
                         return response()->json(["status" => "ERROR", "message" => "You are Already Enrolled In This Current School Year Of <br> <b>" . $enrollmentStatusItem->enrollment_status->school_year_name . "</b> - " . $enrollmentStatusItem . " Term", "data" => []]);
                    }
                    if($nxtGradeLevel['term_type'] != $enrollmentStatusItem->term)
                    {
                        return response()->json(["status" => "ERROR", "message" => "You are NOT Allowed to Enroll In This Current School Year Of <br> <b>" . $enrollmentStatusItem->enrollment_status->school_year_name . "</b> - " . $enrollmentStatusItem . " Term", "data" => []]);
                    }
                }
            }

            if($term->type == "Semester") {
                // Check The Maximum Term And The Term Of Previous Enrollment
                // FIRST
                $previousTerm = ['index'=>null, 'term'=>null];
                $maxTerm = ['index'=>null, 'term'=>null];
                if(count($term->ordinal_terms)>0)
                {
                    foreach ($term->ordinal_terms as $key => $value) {
                        if($value == $enrollment->term_type)
                        {
                            $previousTerm['index'] = $key;
                            $previousTerm['term'] = $value;
                        }
                        // GET LAST TERM OF THE TERM
                        $maxTerm['index'] = $key;
                        $maxTerm['term'] = $value;

                    }
                }
                $index = $previousTerm['index']+1;
                // Check The Next Term Is Greater Than The Max Term
                // If True s
                if($maxTerm['index'] < $index)
                {
                    // Get The Previous Level And Increment Level By Sequence, For The Next Grade Level
                    $previousGradeLevel = YearManagement::with('department')->where('id', $enrollment->level->id)->first();
                    $grade_level        = YearManagement::with('department', 'department.term')->where('sequence', $previousGradeLevel->sequence + 1)->first();

                    if(!$grade_level) {
                         return response()->json(["status" => "ERROR", "message" => "Error Enrolling Student", "data" => []]);
                    }
                    $nxtGradeLevel['schoolYear']           = $enrollmentStatusItem->enrollment_status->schoolYear;
                    $nxtGradeLevel['department']           = $grade_level->department;
                    $nxtGradeLevel['level']                = $grade_level;
                    // $nextEnrollment['track']                = $enrollment->track;
                    $nxtGradeLevel['track']                = $enrollment->track ? TrackManagement::where([
                                                                                        'code' => $enrollment->track->code,
                                                                                        'level_id' => $grade_level->id
                                                                                    ])->first() : null;
                    $nxtGradeLevel['curriculum']           = $enrollment->curriculum;
                    $nxtGradeLevel['commitmentPayment']    = $enrollment->commitmentPayment;
                    $nxtGradeLevel['term_type']            = "First";

                    if($grade_level->department_id != $enrollmentStatusItem->enrollment_status->department_id)
                    {
                        return response()->json(["status" => "ERROR", "message" => "Error Enrolling Student", "data" => []]);
                    }
                }
                else
                {
                    $nextTerm = array_values($term->ordinal_terms)[$index];

                    $nxtGradeLevel['schoolYear']           = $enrollmentStatusItem->enrollment_status->schoolYear;
                    $nxtGradeLevel['department']           = $enrollment->department;
                    $nxtGradeLevel['level']                = $enrollment->level;
                    $nxtGradeLevel['track']                = $enrollment->track;
                    $nxtGradeLevel['curriculum']           = $enrollment->curriculum;
                    $nxtGradeLevel['commitmentPayment']    = $enrollment->commitmentPayment;
                    $nxtGradeLevel['term_type']            = $nextTerm;

                    if($nxtGradeLevel['schoolYear']['id'] != $enrollmentStatusItem->enrollment_status->school_year_id)
                    {
                        return response()->json(["status" => "ERROR", "message" => "You are Already Enrolled In This Current School Year Of <br> <b>" . $enrollmentStatusItem->enrollment_status->school_year_name . "</b> - " . $enrollmentStatusItem . " Term", "data" => []]);
                    }
                    if($nxtGradeLevel['schoolYear']['id'] != $enrollmentStatusItem->enrollment_status->school_year_id || $nxtGradeLevel['term_type'] != $enrollmentStatusItem->term || $nxtGradeLevel['department']['id'] != $enrollmentStatusItem->enrollment_status->department_id)
                    {
                        return response()->json(["status" => "ERROR", "message" => "Error Enrolling Student", "data" => []]);
                        $nxtGradeLevel = null;
                    }
                    else{
                        $nxtGradeLevel['allow']            = 1;
                    }
                }
            }

        } 

        if($nxtGradeLevel['department']->with_track)
        {
            if($enrollment->track_id == null)
            {
                if(!$request->track_id)
                {
                    return response()->json(["status" => "ERROR", "message" => "Selected Track Not Found.", "data" => []]);
                }
                else
                {
                    $track = TrackManagement::where('id', $request->track_id)->first();
                    if(!$track)
                    {
                        return response()->json(["status" => "ERROR", "message" => "Selected Track Not Found.", "data" => []]);
                    }
                    else
                    {
                        $nxtGradeLevel['track']  = $track;
                    }
                }
            }
            else
            {
                $track  = $enrollment->track ? TrackManagement::where([
                                                        'code' => $enrollment->track->code,
                                                        'level_id' => $nxtGradeLevel['level']->id
                                                    ])->first() : null;
                if(!$track)
                {
                   return response()->json(["status" => "ERROR", "message" => "Selected Track Not Found.", "data" => []]);
                }
                else
                {
                    $nxtGradeLevel['track']  = $track;
                }
            }
        }
        else
        {
            $nxtGradeLevel['track']  = null;
        }

        // GET THE YEAR ACTIVE
        $schoolYearActive = SchoolYear::active()->first();


        // CHECK TRACK IF ISSET IF NOT RETURN BACK
        // if(!isset($nxtGradeLevel['track'])) {
        //     return redirect()->back()->withInput();
        // }

        // GET TUITION ACCORDING TO THE GRADE AND YEAR LEVEL
        $track_id = $nxtGradeLevel['track'] !== null ? $nxtGradeLevel['track']->id : null;
        $tuition = Tuition::where([
                        'schoolyear_id' => $enrollmentStatusItem->enrollment_status->school_year_id,
                        'grade_level_id' => $nxtGradeLevel['level']->id,
                        'track_id' => $track_id,
                        'active' => 1
                    ])
                    ->first();

        if($request->show_tuition && ! $tuition) {
            return response()->json(["status" => "ERROR", "message" => "No Tuition Found.", "data" => []]);
        }

        // dd($nxtGradeLevel);


        // if($nxtGradeLevel['grade_level']->id === $request->grade_level_id) { return response()->json(["status" => "ERROR", "message" => "Error, Mismatch Grade Level", "data" => []]); }
        $student = Student::where('studentnumber', $request->studentnumber)->first();
        $initialPayment  = KioskSetting::where('key', 'initial_payment')->first();

        $newEnrollment                        = new Enrollment;
        $newEnrollment->student_id            = $student ? $student->id : null;
        $newEnrollment->studentnumber         = $student->studentnumber;
        $newEnrollment->tuition_id            = $request->show_tuition ? $tuition->id : null;
        $newEnrollment->school_year_id        = $enrollmentStatusItem->enrollment_status->school_year_id;
        $newEnrollment->department_id         = $nxtGradeLevel['department']->id;
        $newEnrollment->level_id              = $nxtGradeLevel['level']->id;
        $newEnrollment->track_id              = $track_id;
        $newEnrollment->curriculum_id         = null;
        $newEnrollment->commitment_payment_id = $request->payment_id;
        $newEnrollment->term_type             = $enrollmentStatusItem->term;
        $newEnrollment->is_applicant          = 1;
        $newEnrollment->old_or_new            = 'old';
        if($initialPayment)
        {
            $newEnrollment->require_payment       = $initialPayment->active;
        }

        $oldEnrollment = Enrollment::where('student_id', $newEnrollment->student_id)
                            ->where('studentnumber', $newEnrollment->studentnumber)
                            ->where('school_year_id', $newEnrollment->school_year_id)
                            ->where('department_id', $newEnrollment->department_id)
                            ->where('level_id', $newEnrollment->level_id)
                            ->where('term_type', $newEnrollment->term_type)
                            ->where('track_id', $newEnrollment->track_id)
                            ->where('term_type', '!=', 'Summer')
                            ->first();
        if($oldEnrollment)
        {
            return response()->json(["status" => "ERROR", "message" => "Error Enrolling Student, Data Already Exist.", "data" => []]);
        }

        if($newEnrollment->save()) {

            $student = Student::where('studentnumber', $request->studentnumber)->first();

            $kioskEnrollment                 = new KioskEnrollment;
            $kioskEnrollment->kiosk_id       = uniqid();
            $kioskEnrollment->student_id     = $student ? $student->id : null;
            $kioskEnrollment->enrollment_id  = $newEnrollment->id;
            $kioskEnrollment->email          = $request->email;
            $kioskEnrollment->student_status = 'old';
            $kioskEnrollment->save();

            $tuition = Tuition::where('id', $request->tuition_id)->first();

            try {
                Mail::to($request->email)->send(new SendMailableKioskAfterEnrolled($newEnrollment, $tuition, $kioskEnrollment, $request->show_tuition));
            } catch (Exception $e) {
                $additionalPage = KioskSetting::where('key', 'additional_page')->first();
                return response()->json(["status" => "OK", "message" => "Successfully Enrolled Student", "data" => $newEnrollment, "additionalPage" => $additionalPage]);
            }

            $additionalPage = KioskSetting::where('key', 'additional_page')->first();
            return response()->json(["status" => "OK", "message" => "Successfully Enrolled Student", "data" => $newEnrollment, "additionalPage" => $additionalPage]);
        }
        return response()->json(["status" => "ERROR", "message" => "Error Enrolling Student", "data" => []]);
    }

    public function levelTrack ($level_id)
    {
        $level  = YearManagement::where('id', $level_id)->first();
        $tracks = TrackManagement::where('level_id', $level_id)->get();
        return $tracks;
    }

    public function department ($id)
    {
        $department = Department::where('id', $id)
                                ->with('term')
                                ->with(['levels' => function ($q) {
                                        $q->select('id', 'year', 'department_id'); 
                                        $q->with('tracks:id,code,level_id');
                                    }
                                ])
                                ->first(['id', 'name']);
        return $department;
    }

    public function downloadPdfForm ($kiosk_id)
    {
        $kiosk = KioskEnrollment::where('kiosk_id', $kiosk_id)->first();
        if($kiosk) {
            $student = Student::where('id', $kiosk->student_id)->with(['schoolYear', 'yearManagement', 'track', 'enrollments'])->first();
            if(!$student) { abort(404, 'Student Not Found'); }
            
            $schoolyear = SchoolYear::where('id', $student->schoolyear)->first();
            if(!$schoolyear) { abort(404, 'SY Not Found'); }

            $pdf = \App::make('dompdf.wrapper');
            $pdf->setPaper(array(0, 0, 612, 936), 'portrait');

            $pdf->loadHTML( view('student.print.print', compact('student')) );
            return $pdf->stream(config('settings.schoolabbr') . $student->studentnumber . '.pdf');
        }

        abort(404);
    }

    public function privacy (Request $request)
    {
        $termsConditions = KioskSetting::where('key', 'terms_conditions')->first();

        return view('kiosk.privacy', compact(['termsConditions']));
    }

    public function getKioskTuitionSetting ()
    {
        $tuitionSetting = KioskSetting::where('key', 'tuition')->first();
        return $tuitionSetting;
    }
}