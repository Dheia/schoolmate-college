<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentSectionAssignment extends Model
{
    use CrudTrait;
    use \Awobaz\Compoships\Compoships;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'student_section_assignments';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'class_code',
        'school_year_id',
        'section_id',
        'term_type',
        'summer',
        'curriculum_id',
        'employee_id',
        'students'
    ];

    protected $appends = ['track', 'level'];

    // protected $casts = ['students'];
    // protected $hidden = [];
    // protected $dates = [];

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

    public function level ()
    {
        return $this->belongsTo("App\Models\YearManagement");
    } 

    public function schoolYear ()
    {
        return $this->belongsTo("App\Models\SchoolYear");
    } 

    public function section ()
    {
        return $this->belongsTo("App\Models\SectionManagement");
    }

    public function employee ()
    {
        return $this->belongsTo("App\Models\Employee");
    }

    public function curriculum ()
    {
        return $this->belongsTo("App\Models\CurriculumManagement");
    } 

    public function levelWithTrashed ()
    {
        return $this->level()->withTrashed();
    } 

    public function schoolYearWithTrashed ()
    {
        return $this->schoolYear()->withTrashed();
    } 

    public function sectionWithTrashed ()
    {
        return $this->section()->withTrashed();
    }

    public function employeeWithTrashed ()
    {
        return $this->employee()->withTrashed();
    } 

    public function curriculumWithTrashed ()
    {
        return $this->curriculum()->withTrashed();
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

    public function getTotalStudentsPerSectionAttribute ()
    {
        if(gettype(json_decode($this->students)) === "array") {
            return count(json_decode($this->students));
        }
        return '0';
    }

    public function getAllStudentsAttribute()
    {
        $students = Student::whereIn('studentnumber', json_decode($this->attributes["students"]))->orderBy('gender')->orderBy('lastname')->orderBy('firstname')->get();
        return $students;
    }

    public function getTrackAttribute ()
    {
        $section = $this->section()->with('track')->first();

        if($section !== null) {
            $track = $section->track;
            if($track !== null) {
                return $track->code;
            }
            return null;
        } 
    }

    public function getLevelAttribute ()
    {
        $level = $this->section()->first();
        return $level !== null ? $level->level_attr : "-";
    }

    public function getDepartmentAttribute ()
    {
        $section    =   $this->section()->with('level')->first();
        $level      =   $section ? $section->level : null;
        return $level ? $level->department_name : "-";
    }

    public function getAdviserAttribute()
    {
        $employee = $this->employeeWithTrashed()->first();
        return $employee ? $employee->full_name : '-';
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MODEL BUTTONS
    |--------------------------------------------------------------------------
    */


}
