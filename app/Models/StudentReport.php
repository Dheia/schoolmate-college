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

class StudentReport extends Model
{
    use \Venturecraft\Revisionable\RevisionableTrait,
        \Awobaz\Compoships\Compoships, 
        SoftDeletes, HasApiTokens, CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'students';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];
    protected $appends = [
        'fullname', 
        'fullname_last_first', 
        'current_enrollment', 
        'is_enrolled', 
        
        'school_year_name',
        'department_name',
        'track_name',
        'level_name',
        'current_level',

        'calculated_age',
        // 'mobile_level'
    ];

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
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */
    public function getFullNameAttribute()
    {
        if($this->middlename) {
            return $this->firstname . ' ' . substr($this->middlename, 0, 1) . '. ' . $this->lastname;
        }
        return $this->firstname . ' ' . $this->lastname;
    }

    public function getFullNameLastFirstAttribute()
    {
        if($this->middlename) {
            return $this->lastname . ', ' .$this->firstname . ' ' .substr($this->middlename, 0, 1) . '. ' ;
        }
        return $this->lastname . ', ' . $this->firstname;
    }
    public function getCurrentEnrollmentAttribute () {
        $current_school_year = SchoolYear::where('isActive', 1)->first();
        $current_enrollment  = Enrollment::where('studentnumber', $this->studentnumber)->where('school_year_id',$current_school_year->id)->first();
        
        if(!$current_enrollment) {
            return null;
        }

        $section             = SectionManagement::find($current_enrollment->section_id);
        if($section) {
            $level = YearManagement::where('id', $current_enrollment->level_id)->first();
            return $level->year . ' - ' . $section->name;
        } else {
            return '';
        }
    }

    public function getCurrentLevelAttribute () {
        $current_school_year = SchoolYear::active()->first();
        
        if($current_school_year === null) {
            $level = YearManagement::where('id', $this->level_id)->first();
            return $level->year;
        } else {
            $current_enrollment = Enrollment::where('studentnumber', $this->studentnumber)->where('school_year_id',$current_school_year->id)->first();

            if($current_enrollment !== null) {
                $level = YearManagement::where('id', $current_enrollment->level_id)->first();
                return $level->year;
            }

            return '-';
        }        
    }
     public function getIsEnrolledAttribute ()
    {
        $current_enrollment = null;
        $term_type = TermManagement::where('department_id', $this->department_id)->first();
        if($term_type)
        {
            if($term_type->type == 'Semester'){
                $current_enrollment = Enrollment::where('studentnumber', $this->studentnumber)
                                            ->where('school_year_id',request()->school_year_id)
                                            ->where('term_type', request()->term_type)
                                            ->first(); 
            }
            else{
                $current_enrollment = Enrollment::where('studentnumber', $this->studentnumber)
                                                ->where('school_year_id',request()->school_year_id)
                                                ->first();  
            }
        }

       
        
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
    public function getCalculatedAgeAttribute() {
        $now = Carbon::now();
        $birthday = Carbon::parse($this->birthdate);
        // return \Carbon\Carbon::parse($this->birthdate)->age() ?? 0;
        return $birthday->diffInYears($now);
    }
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
