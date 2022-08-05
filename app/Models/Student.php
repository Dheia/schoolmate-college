<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

// MODELS
use App\StudentCredential;
use App\Models\YearManagement;
use App\Models\Enrollment;
use App\Models\SchoolYear;
use App\Models\SectionManagement;

// PASSPORT
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;

use Carbon\Carbon;
use Spatie\Activitylog\Traits\LogsActivity;

use Illuminate\Support\Facades\Storage;  

class Student extends Model
{
    use \Venturecraft\Revisionable\RevisionableTrait,
        \Awobaz\Compoships\Compoships, 
        SoftDeletes, HasApiTokens, CrudTrait;

    protected static function boot() {
        parent::boot();

        Student::deleted(function($student) {
            $student->studentQuipperAccount()->delete();
        });
    }

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    protected static $logAttributes = ['*'];
    protected $table = 'students';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'qbo_customer_id',
        'application',
        'lrn',
        'department_id',
        'level_id',
        'track_id',
        'studentnumber',
        'new_or_old',
        'photo',
        'firstname',
        'lastname',
        'middlename',
        'gender',
        'birthdate',
        'citizenship',
        'birthplace',
        'residentialaddress',
        'age',
        'street_number',
        'barangay',
        'city_municipality',
        'province',
        'email',
        'religion',
        'living',
        'legalguardian',
        'legal_guardian_lastname',
        'legal_guardian_firstname',
        'legal_guardian_middlename',
        'legal_guardian_citizenship',
        'legal_guardian_occupation',
        'legal_guardian_contact_number_country_code',
        'legal_guardian_contact_number',
        'contactnumberCountryCode',
        'contactnumber',
        'readingwriting',
        'verbalproficiency',
        'majorlanguages',
        'other_language_specify',
        'otherlanguages',
        'classparticipation',
        'remedialhelp',
        'remedialhelpexplanation',
        'specialtalent',
        'otherinfo',
        'otherinfofield',
        'disciplinaryproblem',
        'disciplinaryproblemexplanation',
        'previousschool',
        'previousschooladdress',
        'schooltable',
        'father',
        'father_living_deceased',
        'fatherfirstname',
        'fatherlastname',
        'fathermiddlename',
        'fathercitizenship',
        'fathervisastatus',
        'father_occupation',
        'fatheremployer',
        'fatherofficenumberCountryCode',
        'fatherofficenumber',
        'fatherdegree',
        'fatherschool',
        'fatherMobileNumberCountryCode',
        'fatherMobileNumber',
        'fatherreceivetext',

        'mother',
        'mother_living_deceased',
        'motherlastname',
        'motherfirstname',
        'mothermiddlename',
        'mothercitizenship',
        'mothervisastatus',
        'mother_occupation',
        'motheremployer',
        'motherOfficeNumberCountryCode',
        'motherOfficeNumber',
        'motherdegree',
        'motherschool',
        'mothernumberCountryCode',
        'mothernumber',
        'motherreceivetext',

        'emergencycontactname',
        'emergencyRelationshipToChild',
        'emergency_contact_other_relation_ship_to_child',
        'emergency_lastname',
        'emergency_firstname',
        'emergency_middlename',
        'emergency_citizenship',
        'emergencyofficephoneCountryCode',
        'emergencyofficephone',
        'emergencymobilenumberCountryCode',
        'emergencymobilenumber',
        'emergencyaddress',
        'emergencyhomephoneCountryCode',
        'emergencyhomephone',
        'isagree',
        'formiscorrect',
        // 'fathersignature',
        // 'mothersignature',
        'date',
        'schoolyear',
        'asthma',
        'asthmainhaler',
        'allergy',
        'allergies',
        'allergyreaction',
        'drugallergy',
        'drugallergies',
        'drugallergyreaction',
        'visionproblem',
        'visionproblemdescription',
        'hearingproblem',
        'hearingproblemdescription',
        'hashealthcondition',
        'healthcondition',
        'ishospitalized',
        'hospitalized',
        'hadinjuries',
        'injuries',
        'medication',
        'medications',
        'schoolhourmedication',
        'firstaidd',
        'emergencycare',
        'hospitalemergencycare',
        'oralmedication',
        // 'parentsignature',
        'date2',

        'father_email',
        'mother_email',
        'legal_guardian_email',
        'emergency_email'
    ];
    protected $hidden = ['qbo_customer_id'];
    // protected $dates = [];
    protected $appends = [
                            'fullname', 
                            'fullname_last_first', 
                            'current_enrollment', 
                            'is_enrolled', 
                            'mother_full_name',
                            'father_full_name',
                            'legal_guardian_full_name',
                            'has_student_credential',
                            'school_year_name',
                            'department_name',
                            'track_name',
                            'level_name',
                            'current_level',
                            'current_department',
                            // 'emergencyRelationshipToChild',
                            'emergency_contact_name_on_record',
                            'emergency_contact_number_on_record',
                            'emergency_contact_home_number_on_record',
                            'emergency_contact_address_on_record',
                            'prefixed_student_number',
                            'calculated_age',
                            'rfid_number'
                            // 'mobile_level'
                        ];
    protected $dates = ['deleted_at'];
    protected $casts = [
        'schooltable'       => 'array',
        'otherlanguages'    => 'array'
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function findForPassport($username) 
    {
        return $this->where('studentnumber', $username)->first();
    }

    public function getSystemUserId()
    {
        try {
            if (class_exists($class = '\SleepingOwl\AdminAuth\Facades\AdminAuth')
                || class_exists($class = '\Cartalyst\Sentry\Facades\Laravel\Sentry')
                || class_exists($class = '\Cartalyst\Sentinel\Laravel\Facades\Sentinel')
            ) {
                return ($class::check()) ? $class::getUser()->id : null;
            } elseif (\Auth::check()) {
                return \Auth::user()->getAuthIdentifier();
            } elseif (backpack_auth()->check()) {
                return backpack_auth()->user()->getAuthIdentifier();
            }
        } catch (\Exception $e) {
            return null;
        }

        return null;
    }

    public static function getOnlineClasses($student_id)
    {
        $classes            =   null;
        $student_section    =   self::getStudentSectionAssignment($student_id);

        if( $student_section ?? '') {
            $classes    =   OnlineClass::with('teacher', 'course', 'course.modules', 'course.modules.topics')
                                    ->where('school_year_id', SchoolYear::active()->first()->id)
                                    ->whereIn('section_id', collect($student_section)->pluck('section_id'))
                                    // ->whereIn('term_type', collect($student_section)->pluck('term_type'))
                                    // ->whereIn('summer', collect($student_section)->pluck('summer'))
                                    ->active()
                                    ->notArchive()
                                    ->orderBy('id', 'DESC')
                                    ->get();
        }
        return $classes;
    }

    public static function getStudentSectionAssignment($student_id)
    {
        $student            =   Student::where('id', $student_id)->first();
        $schoolYear         =   SchoolYear::active()->first();
        $student_section    =   [];

        if(!$student || !$schoolYear){
            return null;
        }

        if(!$student->studentnumber){
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
                                ->where('studentnumber', $student->studentnumber)
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

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function studentQuipperAccount ()
    {
        return $this->hasOne(QuipperStudentAccount::class);
    }

    public function webmail ()
    {
        return $this->morphOne(Webmail::class, 'webmailable');
    }

    public function studentCredential ()
    {
        return $this->hasOne('App\StudentCredential', 'studentnumber', 'studentnumber');
    }



    public function schoolYear ()
    {
        return $this->belongsTo(SchoolYear::class, 'schoolyear'); 
    }

    public function yearManagement ()
    {
        return $this->belongsTo(YearManagement::class, 'level_id');
    }

    public function level ()
    {        
        return $this->belongsTo(YearManagement::class, 'level_id');
    }

    public function tuition ()
    {
        return $this->belongsToMany(Tuition::class)->withPivot(['schoolyear_id', 'grade_level_id']);
    }
    
    public function rfid()
    {
        return $this->hasOne(Rfid::class,'studentnumber','studentnumber');
    }

    public function locker()
    {
        return $this->hasOne(LockerInventory::class,'studentnumber','studentnumber');
    }
    
    public function enrollments() 
    {
        return $this->hasMany(Enrollment::class,'studentnumber','studentnumber');
    }

    public function requirement()
    {
        return $this->hasOne(Requirement::class);
    }

    public function turnstilelogs()
    {
        return $this->hasManyThrough(TurnstileLog::class,Rfid::class,'studentnumber','rfid','studentnumber','studentnumber');
    }

    public function track ()
    {
        return $this->belongsTo(TrackManagement::class);
    }

    public function department ()
    {
        return $this->belongsTo(Department::class);
    }

    public function submittedAssignments ()
    {
        return $this->hasMany(StudentSubmittedAssignment::class);
    }

    public function submittedQuizzes ()
    {
        return $this->hasMany(StudentQuizResult::class, 'studentnumber', 'studentnumber');
    }

    public function comments()
    {
        return $this->morphMany(OnlineComment::class, 'commentable');
    }

    public function systemAttendances()
    {
        return $this->morphMany(SystemAttendance::class, 'user');
    }

    public function onlineClassAttendances()
    {
        return $this->morphMany(OnlineClassAttendance::class, 'user');
    }

    public function goals()
    {
        return $this->morphMany(Goal::class, 'user');
    }

    public function referredBy()
    {
        return $this->hasOne(Referral::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeLastEight($query)
    {
        return $query->limit(8);
    }

    // GET ALL STUDENT ATTENDANCE LOGS
    // public function getAttendanceLogs ()
    // {
    //     $this->hasMany('App\Models')
    // }

    // SCOPE
    public function scopeIsEnrolled ($query)
    {
        $enrolled   =   Student::join('enrollments', function ($join) {
                                $join->on('students.studentnumber', 'enrollments.studentnumber');
                            })->where('enrollments.school_year_id', SchoolYear::active()->first()->id)
                            ->where('enrollments.is_applicant', 0)
                            ->select('students.*')
                            ->get();

        $students = $query->whereIn('students.studentnumber', collect($enrolled)->pluck('studentnumber'))->get();
        // dd($students->get());
        return $students;
    }
    public function scopeApplicant ($query)
    {
        $enrolled   =   Student::join('enrollments', function ($join) {
                                $join->on('students.studentnumber', 'enrollments.studentnumber');
                            })->where('enrollments.school_year_id', SchoolYear::active()->first()->id)
                            ->where('enrollments.is_applicant', 0)
                            ->select('students.*')
                            ->get();

         // dd(collect($enrolled)->pluck('studentnumber'));
        $students = $query->whereNotIn('students.studentnumber', collect($enrolled)->pluck('studentnumber'))->get();
        // dd($students);
        return $students;
    }



    public function scopeClassList ($query)
    {
        $enrollments = $query->with('enrollments');
        return $enrollments;
    }
    public function scopeTrackCode ($query, $track_code)
    {
        $students = $query->join('track_managements', function ($join) {
            $join->on('students.track_id', 'track_managements.id');
        })->where('track_managements.code', $track_code)->select('students.*');
        // dd($students->get());
        return $students;
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    public function getFullNameAttribute()
    {
        return $this->firstname . ' ' . substr($this->middlename, 0, 1) . '. ' . $this->lastname;
    }

    public function getFullNameLastFirstAttribute()
    {
        if($this->middlename) {
            return $this->lastname . ', ' .$this->firstname . ' ' .substr($this->middlename, 0, 1) . '. ' ;
        }
        return $this->lastname . ', ' . $this->firstname;
    }

    public function getPrefixedStudentNumberAttribute ()
    {
        return strtoupper(config('settings.schoolabbr')) . ' - ' . $this->studentnumber;
    }

    public function getLevelAppliedForAttribute()
    {
        $student = Student::with('level')->where('id',$this->id)->first();


        if($student){
            return $student->level->year ?? null;
        }else{
            return '-';
        }
    }

    // public function getTrackAttribute(){
    //     $student = Student::with('level')->where('id',$this->id)->first();
    //     // dd($student->level->track);
    //     if($student){
    //         return $student->level->track ?? null;
    //     }else{
    //         return '';
    //     }

        
    // }

    // public function getYearAttribute(){
    //     $year = YearManagement::where('id', $this->level_id)->first();
    //     return $year->year;
    // }

    public function getCurrentEnrollmentAttribute () {
        $current_school_year = SchoolYear::where('isActive', 1)->first();
        $current_enrollment  = Enrollment::where('studentnumber', $this->studentnumber)->where('school_year_id',$current_school_year['id'])->first();
        
        $section = $current_enrollment ? SectionManagement::find($current_enrollment['section_id']) : null;
                
        if($section) {
            $level = YearManagement::where('id', $current_enrollment['level_id'])->first();
            return $level['year'] . ' - ' . $section['name'];
        } else {
            return '';
        }
    }

    public function getCurrentLevelAttribute () {
        $current_school_year = SchoolYear::where('isActive', 1)->first();
        
        if($current_school_year === null) {
            $level = YearManagement::where('id', $this->level_id)->first();
            return $level->year;
        } else {
            $current_enrollment = Enrollment::where('studentnumber', $this->studentnumber)->where('school_year_id',$current_school_year->id)->first();

            if($current_enrollment !== null) {
                $level = YearManagement::where('id', $current_enrollment->level_id)->first();
                return $level ? $level->year : '-';
            }

            return '-';
        }        
    }

    public function getCurrentDepartmentAttribute () {
        $current_school_year = SchoolYear::where('isActive', 1)->first();
        
        if($current_school_year === null) {
            $department = Department('id', $this->department_id)->first();
            return $department->name;
        } else {
            $current_enrollment = Enrollment::where('studentnumber', $this->studentnumber)->where('school_year_id',$current_school_year->id)->first();

            if($current_enrollment !== null) {
                $department = Department::where('id', $current_enrollment->department_id)->first();
                return $department ? $department->name : '-';
            }

            return '-';
        }        
    }

    public function getPhotoAttribute(){
        if(isset($this->attributes['photo'])) {
            if($this->attributes['photo'] !== null){
                $photo = $this->attributes['photo'];
                if(\Storage::disk('public')->exists($photo)) {
                    $photo = 'storage/'.$this->attributes['photo'];
                    return $photo;
                } else {
                    return 'images/headshot-default.png';
                }
            } else {
                return 'images/headshot-default.png';
            }
        } else {
            return 'images/headshot-default.png';
        }
    }

    public function getOrigPhotoAttribute(){
        if(isset($this->attributes['photo'])) {
            if($this->attributes['photo'] !== null){
                $photo = $this->attributes['photo'];
                if(\Storage::disk('public')->exists($photo)) {
                    return $photo;
                } else {
                    return 'images/headshot-default.png';
                }
            } else {
                return 'images/headshot-default.png';
            }
        } else {
            return 'images/headshot-default.png';
        }
    }

    public function getPhotoBase64Attribute ()
    {
        // dd();
        if(!str_contains('images/headshot-default.png', $this->photo)){
            $path   = \Storage::disk('public')->getAdapter($this->attributes['photo'])->getPathPrefix() . $this->attributes['photo'];

            if(file_exists($path)) {
                $type   = pathinfo($path, PATHINFO_EXTENSION);
                $data   = file_get_contents($path);
                $base64 =  base64_encode($data);
                return $base64;
            } else {
                $path   = public_path('images/headshot-default.png');
                $type   = pathinfo($path, PATHINFO_EXTENSION);
                $data   = file_get_contents($path);
                $base64 = base64_encode($data);
                return $base64;
            }
            // $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        } else {
            $path   = public_path('images/headshot-default.png');
            $type   = pathinfo($path, PATHINFO_EXTENSION);
            $data   = file_get_contents($path);
            $base64 = base64_encode($data);
            return $base64;
        }
    }

    public function getRfidNumberAttribute ()
    {
        $rfid = $this->rfid()->first();
        if($rfid !== null) {
            return $rfid->rfid;
        }
        return null;
    }

    // public function 

    // public function getAgeAttribute()
    // {
    //     return Carbon::parse($this->attributes['birthdate'])->age;
    // }

    public function getResidentialAddressAttribute ()
    {
        return $this->street_number . ' ' . $this->barangay . ' ' . $this->city_municipality . ' ' . $this->province;
    }

    public function getFatherFullNameAttribute ()
    {
        return $this->fatherfirstname . ' ' . $this->fathermiddlename . ' ' . $this->fatherlastname;
    }

    public function getMotherFullNameAttribute ()
    {
        return $this->motherfirstname . ' ' . $this->mothermiddlename . ' ' . $this->motherlastname;
    }

    public function getLegalGuardianFullNameAttribute ()
    {
        return $this->legal_guardian_firstname . ' ' . $this->legal_guardian_middlename . ' ' . $this->legal_guardian_lastname;
    }

    public function getHasStudentCredentialAttribute ()
    {
        $isExist = StudentCredential::where('studentnumber', $this->studentnumber)->exists();
        return $isExist ?? false;
    }

    public function getMobileLevelAttribute ()
    {
        // get active school year
        $school_year = SchoolYear::active()->first();
        $level_and_schoolyear = null;
        if($school_year !== null) {
            
            // get latest enrollment from active school year
            $enrollment = Enrollment::where(['studentnumber' => $this->studentnumber, 'school_year_id' => $school_year->id])->first();

            $level = YearManagement::where('id', $enrollment->level_id ?? null)->first();

            $level_and_schoolyear = [
                'level' => $level->year ?? null,
                'school_year' => $school_year->schoolYear ?? null
            ];

            return $level_and_schoolyear;
        }

        return $level_and_schoolyear;
    }

    public function getEmergencyContactNameOnRecordAttribute(){
        if($this->emergencyRelationshipToChild == "Mother"){
            return strtoupper($this->motherfirstname . " " . $this->motherlastname);
        }else if($this->emergencyRelationshipToChild == "Father"){
            return strtoupper($this->fatherfirstname . " " . $this->fatherlastname);
        }else if($this->emergencyRelationshipToChild == "LegalGuardian"){
            return strtoupper($this->legal_guardian_firstname . " " . $this->legal_guardian_lastname);
        }else if($this->emergencyRelationshipToChild){
            return strtoupper($this->emergencycontactname);
        }else if($this->emergency_firstname != null && $this->emergency_lastname != null){
            return strtoupper($this->emergency_firstname . " " . $this->emergency_lastname);
        }
        return "Not Set";
    }

    public function getEmergencyContactNumberOnRecordAttribute(){
        if($this->emergencyRelationshipToChild == "Mother"){
            $str = str_replace("+63 0", "+63", $this->mothernumber);
            return $str;
        }
        else if($this->emergencyRelationshipToChild == "Father"){
            $str = str_replace("+63 0", "+63", $this->fatherMobileNumber);
            return $str;
        }else if($this->emergencyRelationshipToChild == "LegalGuardian"){
            $str = str_replace("+63 0", "+63", $this->legal_guardian_contact_number);
            return $str;
        }else if($this->emergencyRelationshipToChild){
            $str = str_replace("+63 0", "+63", $this->emergencymobilenumber);
            return $str;
        }
        return "Contact Number not Set";
    }

    public function getEmergencyContactHomeNumberOnRecordAttribute(){
        if($this->emergencyRelationshipToChild == "Mother"){
            $str = str_replace("+63 0", "+63", $this->mothernumber);
            return $str;
        }
        else if($this->emergencyRelationshipToChild == "Father"){
            $str = str_replace("+63 0", "+63", $this->fatherMobileNumber);
            return $str;
        }else if($this->emergencyRelationshipToChild == "LegalGuardian"){
            $str = str_replace("+63 0", "+63", $this->legal_guardian_contact_number);
            return $str;
        }else if($this->emergencyRelationshipToChild){
            $str = str_replace("+63 0", "+63", $this->emergencymobilenumber);
            return $str;
        }
        return "Contact Number not Set";
    }

    public function getEmergencyContactAddressOnRecordAttribute(){
        if($this->emergencyaddress){
            return ucwords($this->emergencyaddress);
        }else if($this->emergencyRelationshipToChild == "Father"){
            return ucwords($this->residentialaddress . " " . $this->street_number . " " . $this->barangay . " " . $this->city_municipality . " " . $this->province);
        }else if($this->emergencyRelationshipToChild == "LegalGuardian"){
            return ucwords($this->residentialaddress . " " . $this->street_number . " " . $this->barangay . " " . $this->city_municipality . " " . $this->province);
        }
        else if($this->emergencyRelationshipToChild == "Mother"){
            return ucwords($this->street_number . " " . $this->barangay . " " . $this->city_municipality . " " . $this->province);
            
        }
        return "Address Not Set";
    }

    public function getCalculatedAgeAttribute() {
        $now = Carbon::now();
        $birthday = Carbon::parse($this->birthdate);
        // return \Carbon\Carbon::parse($this->birthdate)->age() ?? 0;
        return $birthday->diffInYears($now);
    }

    public function getIsEnrolledAttribute ()
    {
        $current_school_year = SchoolYear::where('isActive', 1)->first();
        $current_enrollment = Enrollment::where('studentnumber', $this->studentnumber)
                                            ->where('school_year_id',$current_school_year['id'])
                                            ->first();
        
        if($current_enrollment){
            return 'Enrolled';
        }else{
            return 'Applicant';
        }
    }

    public function getSchoolYearNameAttribute ()
    {
        $sy = self::schoolYear()->first();
        if($sy !== null) {
            return $sy->schoolYear;
        }
        return null;
    }

    public function getDepartmentNameAttribute ()
    {
        $department = $this->department()->first();
        if($department !== null) {
            return $department->name;
        }
        return null;
    }

    public function getTrackNameAttribute ()
    {
        $track = $this->track()->first();
        if($track !== null) {
            return $track->code;
        }
        return null;
    }

    public function getLevelNameAttribute ()
    {
        $level = $this->level()->first();
        if($level !== null) {
            return $level->year;
        }
        return null;
    }

    public function getStudentSectionNameAttribute(){

        $student_section    =   null;
        $enrollment         =   Enrollment::orderBy('term_type', 'DESC')
                                ->with(['schoolYear', 'level', 'student', 'schoolYear', 'tuition', 'commitmentPayment', 'track'])
                                ->where('school_year_id', SchoolYear::active()->first()->id)
                                ->where('studentnumber', $this->studentnumber)
                                ->first();
        if(!$enrollment){
            return null;
        }

        // Get All Section Section 
        // $sections                   =   SectionManagement::where('level_id', $enrollment->level_id)
        //                                                 ->where('track_id', $enrollment->track_id)
        //                                                 ->where('curriculum_id', $enrollment->curriculum_id)
        //                                                 ->get();
        $sections                   =   SectionManagement::where('level_id', $enrollment->level_id)
                                                        ->where('track_id', $enrollment->track_id)
                                                        ->get();
        if(!$sections){
            return null;
        }
        $sections_ids               =   collect($sections)->pluck('id');

        $studentSectionAssignments  =   StudentSectionAssignment::with('section')
                                                        ->whereIn('section_id', $sections_ids)
                                                        ->where('school_year_id', $enrollment->school_year_id)
                                                        ->get();
        if(!$studentSectionAssignments){
            return null;
        }
        // Check User if in sections
        foreach ($studentSectionAssignments as $key => $studentSectionAssignment) {

            $students   = Student::whereIn('studentnumber', json_decode($studentSectionAssignment->students))
                                ->where('studentnumber', $enrollment->studentnumber)
                                ->get();

            if(count($students) > 0){
                $student_section = $studentSectionAssignment;
            }
        }
        
        if(!$student_section){
             return null;
        }
        if($student_section->section)
        {
            return $student_section->section->name;
        }
        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    public function setPhotoAttribute($value)
    {
        $attribute_name = 'photo';
        $disk = 'public';
        $destination_path = 'uploads/students/';

        // if the image was erased
        if ($value==null) {
            // delete the image from disk
            \Storage::disk($disk)->delete($this->{$attribute_name});

            // set null in the database column
            $this->attributes[$attribute_name] = null;
        }

        // if a base64 was sent, store it in the db
        if (starts_with($value, 'data:image'))
        {
            // 0. Make the image
            $image = \Image::make($value);
            // 1. Generate a filename.
            $filename = md5($value.time()).'.jpg';
            // 2. Store the image on disk.
            \Storage::disk($disk)->put($destination_path.'/'.$filename, $image->stream());
            // 3. Save the path to the database
            $this->attributes[$attribute_name] = $destination_path.'/'.$filename;
        }
    }

    public function setAgeAttribute ($value)
    {
        $this->attributes['age'] = Carbon::parse($this->birthdate)->age;
    }

}
