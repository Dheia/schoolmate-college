<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

use App\SelectedOtherProgram;
use App\PaymentHistory;
use Illuminate\Database\Eloquent\SoftDeletes;

use Carbon\Carbon;
use Spatie\Activitylog\Traits\LogsActivity;

use App\StudentCredential;

class Enrollment extends Model
{
    use CrudTrait;
    use \Awobaz\Compoships\Compoships;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'enrollments';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
                            'student_id',
                            'studentnumber',
                            'qr_code',
                            'school_year_id',
                            'curriculum_id',
                            'level_id',
                            'department_id',
                            'track_id',
                            'tuition_id',
                            // 'section_id',
                            'commitment_payment_id',
                            'term_type',
                            'is_applicant',
                            'is_passed',
                            'old_or_new',
                            'require_payment'
                        ];
    // protected $hidden = [];
    // protected $dates = [];
    protected $appends = [
        'lrn',
        'full_name',
        'firstname',
        'middlename',
        'lastname',
        'gender',
        'total_tuition',
        'total_discounts_discrepancies',
        'total_payment_histories',
        'remaining_balance',
        'school_year_name',
        'department_name',
        'track_name',
        'level_name',
        'tuition_name',
        'payment_method_name',
        'date_enrolled',
        'enrollment_status',

        'emergency_contact_name_on_record',
        'emergency_contact_number_on_record',
        'emergency_contact_address_on_record',

        'referred',
        'referrer_contact',

        'rendered_qr_code'
    ];
    // protected $dates = ['deleted_at', 'birth_date'];
    protected $casts = [
        // 'created_at' => 'date_format:d/m/yyyy',
        'proof_of_payment'          => 'array',
    ];

    protected static $logAttributes = ['*'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function QBOInvoices ()
    {
        return $this->hasMany(EnrollmentQboInvoice::class, 'enrollment_id');
    }

    public function level ()
    {
        return $this->belongsTo(YearManagement::class);
    }


    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function tuition ()
    {
        return $this->belongsTo(Tuition::class);
    }

    public function department ()
    {
        return $this->belongsTo(Department::class);
    }
    public function track ()
    {
        return $this->belongsTo(TrackManagement::class);
    }

        // *************************** //
       // --------------------------- //
      // METHODS FOR STUDENT ACCOUNT //
     // --------------------------- //
    // *************************** //

    public function tuitions ()
    {
        // {hasMany[FOREIGN KEY], [LOCAL KEY]} 
        return $this->hasMany(Tuition::class, 'id', 'tuition_id');
    }

    public function otherPrograms ()
    {
        OtherProgram::findMany($this->selectedOtherPrograms->pluck('id'));
    }

       // ---------------------------------- //
      // END OF METHODS FOR STUDENT ACCOUNT //
     // ---------------------------------- //

    public function section ()
    {
        return $this->belongsTo(SectionManagement::class);
    }

    public function curriculum ()
    {
        return $this->belongsTo(CurriculumManagement::class);
    }

    public function students ()
    {
        return $this->belongsTo(Student::class, 'studentnumber', 'studentnumber');
    }

    public function studentsById ()
    {
       return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function student ()
    {
        if($this->student_id !== null) {
            return $this->belongsTo(Student::class);
        } else {
            return $this->belongsTo(Student::class, 'studentnumber', 'studentnumber');
        }
    }

    public function studentById ()
    {
        return $this->hasOne(Student::class, 'id', 'student_id');
    }

    public function commitmentPayment()
    {
        return $this->belongsTo(CommitmentPayment::class);
    }

    public function selectedOtherPrograms ()
    {
        return $this->hasMany('App\SelectedOtherProgram', 'enrollment_id')->with('otherProgram');
    }

    public function selectedOtherServices ()
    {
        return $this->hasMany('App\SelectedOtherService', 'enrollment_id')->with('otherService');
    }

    public function additionalFees ()
    {
        return $this->hasMany('App\AdditionalFee', 'enrollment_id');
    }

    public function specialDiscounts ()
    {
        return $this->hasMany(SpecialDiscount::class, 'enrollment_id');
    }

    public function selectedPaymentType ()
    {
        return $this->hasOne('App\SelectedPaymentType');
    }

    public function paymentHistories ()
    {
        return $this->hasMany(PaymentHistory::class, 'enrollment_id');
    }

    public function paynamicsPayments ()
    {
        return $this->hasMany(PaynamicsPayment::class, 'enrollment_id');
    }

    public function studentSectionAssignment ()
    {
        return $this->hasOne(StudentSectionAssignment::class, 'school_year_id', 'school_year_id');
    }

    public function kioskEnrollment ()
    {
        return $this->hasOne(KioskEnrollment::class, 'enrollment_id');
    }

    public function discrepancies ()
    {
        return $this->hasMany(Discrepancy::class, 'enrollment_id');
    }

    public function studentCredential ()
    {
        return $this->hasOne(StudentCredential::class, 'studentnumber', 'studentnumber');
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    // public function scopeLevelId($query)
    // {
    //     return $query->where('section_id');
    // }
    public function scopeTrackCode ($query, $track_code)
    {
        $enrollments = $query->join('track_managements', function ($join) {
            $join->on('enrollments.track_id', 'track_managements.id');
        })->where('track_managements.code', $track_code)->select('enrollments.*');
        // dd($students->get());
        return $enrollments;
    }
    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    public function getLrnAttribute ()
    {
        $student = $this->student()->first();
        return $student !== null ? $student->lrn : '-';
    }

    public function getIsAccountDisabledAttribute ()
    {
        $studentCredential = $this->studentCredential();
        return $studentCredential !== null ? Carbon::parse($student->birthdate)->age : '0';
    }

    public function getAgeAttribute ()
    {
        $student = $this->student()->first();
        return $student !== null ? Carbon::parse($student->birthdate)->age : '-';
    }

    public function getGenderAttribute ()
    {
        $student = $this->student()->first();
        return $student !== null ? $student->gender : '-';
    }
    
    public function getBirthDateAttribute ()
    {
        $student = $this->student()->first();
        return $student !== null ? $student->birthdate : null;
    }
    
    public function getCitizenshipAttribute ()
    {
        $student = $this->student()->first();
        return $student !== null ? $student->citizenship : '-';
    }
    
    public function getBirthplaceAttribute ()
    {
        $student = $this->student()->first();
        return $student !== null ? $student->birthplace : '-';
    }

    public function getResidentialAddressAttribute ()
    {
        $student = $this->student()->first();
        return $student !== null ? $student->residentialaddress : '-';
    }

    public function getReligionAttribute ()
    {
        $student = $this->student()->first();
        return $student !== null ? $student->religion : '-';
    }

    public function getFullNameAttribute ()
    {
        if($this->student_id !== null) {
            $student = Student::where('id', $this->student_id)->first();
            if($student !== null) {
                return $student->firstname . ' ' . $student->lastname;
            } 
        } else {
            $student = Student::where('studentnumber', $this->studentnumber)->first();
            if($student !== null) {
                return $student->firstname . ' ' . $student->lastname;
            }
        }

        return "Unknown";
    }

    public function getFirstnameAttribute() {
        if($this->student_id !== null) {
            $student = Student::where('id', $this->student_id)->first();
            if($student !== null) {
                return $student->firstname;
            } 
        } else {
            $student = Student::where('studentnumber', $this->studentnumber)->first();
            if($student !== null) {
                return $student->firstname;
            }
        }

        return "Unknown";
    }

    public function getMiddlenameAttribute() {
        if($this->student_id !== null) {
            $student = Student::where('id', $this->student_id)->first();
            if($student !== null) {
                return $student->middlename;
            } 
        } else {
            $student = Student::where('studentnumber', $this->studentnumber)->first();
            if($student !== null) {
                return $student->middlename;
            }
        }

        return "Unknown";
    }

    public function getDateEnrolledAttribute(){
        return Carbon::parse($this->created_at)->toDateString();
    }

    public function getLastnameAttribute() {
        if($this->student_id !== null) {
            $student = Student::where('id', $this->student_id)->first();
            if($student !== null) {
                return $student->lastname;
            } 
        } else {
            $student = Student::where('studentnumber', $this->studentnumber)->first();
            if($student !== null) {
                return $student->lastname;
            }
        }

        return "Unknown";
    }

    public function getTuitionFeeNameAttribute(){
        $tuitionname = Tuition::where('id', $this->tuition_id)->first();

        if($tuitionname !== null) {
            return $tuitionname->form_name;
        }

        return "Not Tagged";
    }

    public function getRemainingBalanceAttribute ()
    {
        $tuition = 0;

        if(!$this->tuition()->first()) { return $tuition; }

        foreach ($this->tuition()->first()->grand_total as $grandTotal) {
            if($grandTotal['payment_type'] == $this->commitment_payment_id) {
                $tuition = $grandTotal['amount'];
            }
        }

             // total = parseFloat(value.amount) - 
             //      parseFloat(totalPaymentHistory) + 
             //      parseFloat(totalSelectedOtherProgram) + 
             //      parseFloat(totalSelectedOtherService) - 
             //      parseFloat(totalSpecialDiscount) + 
             //      parseFloat(totalAdditionalFee) - 
             //      parseFloat(totalDiscrepancy);

        $totalOtherPrograms     = $this->selectedOtherPrograms()->get()->sum('otherProgram.amount');
        $totalOtherServices     = $this->selectedOtherServices()->get()->sum('otherService.amount');
        $totalAdditionalFees    = $this->additionalFees()->get()->sum('amount');
        $totalspecialDiscounts  = $this->specialDiscounts()->get()->sum('amount');
        $totalPaymentHistories  = $this->paymentHistories()->get()->sum('amount'); 
        $totalDiscrepancies     = $this->discrepancies()->get()->sum('amount'); 

        return  ($tuition + $totalOtherPrograms + $totalOtherServices + $totalAdditionalFees - $totalDiscrepancies - $totalspecialDiscounts) - $totalPaymentHistories;
    }

    public function getTotalTuitionAttribute ()
    {
        $tuition = 0;

        if(!$this->tuition()->first()) { return $tuition; }

        foreach ($this->tuition()->first()->grand_total as $grandTotal) {
            if($grandTotal['payment_type'] == $this->commitment_payment_id) {
                $tuition = $grandTotal['amount'];
            }
        }

             // total = parseFloat(value.amount) - 
             //      parseFloat(totalPaymentHistory) + 
             //      parseFloat(totalSelectedOtherProgram) + 
             //      parseFloat(totalSelectedOtherService) - 
             //      parseFloat(totalSpecialDiscount) + 
             //      parseFloat(totalAdditionalFee) - 
             //      parseFloat(totalDiscrepancy);

        $totalOtherPrograms     = $this->selectedOtherPrograms()->get()->sum('otherProgramWithTrashed.amount');
        $totalOtherServices     = $this->selectedOtherServices()->get()->sum('otherServiceWithTrashed.amount');
        $totalAdditionalFees    = $this->additionalFees()->withTrashed()->get()->sum('amount');

        return  ($tuition + $totalOtherPrograms + $totalOtherServices + $totalAdditionalFees);
    }

    public function getTotalDiscountsDiscrepanciesAttribute ()
    {
        $totalspecialDiscounts  = $this->specialDiscounts()->get()->sum('amount');
        $totalDiscrepancies     = $this->discrepancies()->get()->sum('amount'); 

        return ($totalspecialDiscounts + $totalDiscrepancies);
    }

    public function getTotalAdditionalFeesAttribute ()
    {
        return $this->additionalFees()->withTrashed()->get()->sum('amount');
    }

    public function getTotalOtherProgramsAttribute ()
    {
        return $this->selectedOtherPrograms()->get()->sum('otherProgramWithTrashed.amount');
    }

    public function getTotalOtherServicesAttribute ()
    {
        return $this->selectedOtherServices()->get()->sum('otherServiceWithTrashed.amount');
    }

    public function getTotalPaymentHistoriesAttribute()
    {
        $totalPaymentHistories  = $this->paymentHistories()->get()->sum('amount');
        return $totalPaymentHistories;
    }

    public function getCurrentSchoolYearAttribute(){
        return SchoolYear::whereActive()->first();
    }

    public function getSchoolYearNameAttribute ()
    {
        $sy = $this->schoolYear()->first();
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

    public function getTuitionNameAttribute ()
    {
        $tuition = $this->tuition()->first();
        if($tuition !== null) {
            return $tuition->form_name;
        }
        return null;
    }

    public function getPaymentMethodNameAttribute ()
    {
        $commitmentPayment = $this->commitmentPayment()->first();
        if($commitmentPayment !== null) {
            return $commitmentPayment->name;
        }
        return null;
    }

    public function getEnrollmentStatusAttribute ()
    {
        return $this->is_applicant ? 'Applicant' : 'Enrolled';
    }

    public function getEmailAttribute ()
    {
        $student = $this->student()->first();
        $email = $student;
        if($student)
        {
            $email = $student->email;
        }
        return $email ? $email : "-";
    }

    public function getEmergencyContactAddressOnRecordAttribute(){
        $student = $this->student()->first();
        if(!$student) {
            return "Address Not Set";
        }

        if($student->emergencyaddress){
            return ucwords($student->emergencyaddress);
        }else if($student->emergencyRelationshipToChild == "Father"){
            return ucwords($student->residentialaddress . " " . $student->street_number . " " . $student->barangay . " " . $student->city_municipality . " " . $student->province);
        }else if($student->emergencyRelationshipToChild == "LegalGuardian"){
            return ucwords($student->residentialaddress . " " . $student->street_number . " " . $student->barangay . " " . $student->city_municipality . " " . $student->province);
        }
        else if($student->emergencyRelationshipToChild == "Mother"){
            return ucwords($student->street_number . " " . $student->barangay . " " . $student->city_municipality . " " . $student->province);
            
        }
        return "Address Not Set";
    }

    public function getEmergencyContactNameOnRecordAttribute(){
        $student = $this->student()->first();
        if(!$student) {
            return "Not Set";
        }
        if($student->emergencyRelationshipToChild == "Mother"){
            return strtoupper($student->motherfirstname . " " . $student->motherlastname);
        }else if($student->emergencyRelationshipToChild == "Father"){
            return strtoupper($student->fatherfirstname . " " . $student->fatherlastname);
        }else if($student->emergencyRelationshipToChild == "LegalGuardian"){
            return strtoupper($student->legal_guardian_firstname . " " . $student->legal_guardian_lastname);
        }else if($student->emergencyRelationshipToChild){
            return strtoupper($student->emergencycontactname);
        }else if($student->emergency_firstname != null && $student->emergency_lastname != null){
            return strtoupper($student->emergency_firstname . " " . $student->emergency_lastname);
        }
        return "Not Set";
    }

     public function getEmergencyContactNumberOnRecordAttribute(){
        $student = $this->student()->first();
        if(!$student) {
             return "Contact Number not Set";
        }

        if($student->emergencyRelationshipToChild == "Mother"){
            $str = str_replace("+63 0", "+63", $student->mothernumber);
            return $str;
        }
        else if($student->emergencyRelationshipToChild == "Father"){
            $str = str_replace("+63 0", "+63", $student->fatherMobileNumber);
            return $str;
        }else if($student->emergencyRelationshipToChild == "LegalGuardian"){
            $str = str_replace("+63 0", "+63", $student->legal_guardian_contact_number);
            return $str;
        }else if($student->emergencyRelationshipToChild){
            $str = str_replace("+63 0", "+63", $student->emergencymobilenumber);
            return $str;
        }
        return "Contact Number not Set";
    }

    public function getRenderedQrCodeAttribute() 
    {
        if($this->qr_code) {
            $qrCode = \QrCode::size(150)->generate($this->qr_code);
            return $qrCode;
        } else {
            return null;
        }
    }

    public function getReferredAttribute()
    {
        $student = $this->student()->first();
        if(! $student) {
            return '-';
        }

        if(! $student->referredBy) {
            return '-';
        }

        return strtoupper($student->referredBy->medium) . ' - ' . $student->referredBy->referred_by;
    }

    public function getReferrerContactAttribute()
    {
        $student = $this->student()->first();
        if(! $student) {
            return '-';
        }

        if(! $student->referredBy) {
            return '-';
        }

        return $student->referredBy->contact;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
