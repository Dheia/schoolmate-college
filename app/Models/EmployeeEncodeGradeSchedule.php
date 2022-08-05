<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeEncodeGradeSchedule extends Model
{
    use CrudTrait;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'employee_encode_grade_schedules';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'employee_id',
        'school_year_id',
        'term_type',
        'section_id',
        'subject_id',
        'start_at',
        'end_at'
    ];
    // protected $hidden = [];
    protected $dates = ['start_at', 'end_at'];
    protected $appends = ['department_id', 'level_id', 'track_id'];

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

    public function employee ()
    {
        return $this->belongsTo(Employee::class);
    }

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function section ()
    {
        return $this->belongsTo(SectionManagement::class);
    }

    public function subject ()
    {
        return $this->belongsTo(SubjectManagement::class);
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

    public function getDepartmentIdAttribute ()
    {
        $section = $this->section()->first();
        if($section) {
            $level = $section->level;
            return $level ? $level->department_id : null;
        }
        return null;
    }

    public function getLevelIdAttribute ()
    {
        $section = $this->section()->first();
        if($section) {
            $level = $section->level;
            return $level ? $level->id : null;
        }
        return null;
    }

    public function getLTrackIdAttribute ()
    {
        $section = $this->section()->first();
        if($section) {
            $track = $section->track;
            return $track ? $track->id : null;
        }
        return null;
    }

    public function getDepartmentNameAttribute ()
    {
        $section = $this->section()->first();
        if($section) {
            $level = $section->level;
            if($level) {
                return $level->department ? $level->department->name : '-';
            }
        }
        return '-';
    }

    public function getLevelNameAttribute ()
    {
        $section = $this->section()->first();
        if($section) {
            $level = $section->level;
            return $level ? $level->year : '-';
        }
        return '-';
    }

    public function getTrackNameAttribute ()
    {
        $section = $this->section()->first();
        if($section) {
            $track = $section->track;
            return $track ? $track->code : '-';
        }
        return '-';
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
