<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

use Carbon\Carbon;

class SetupGrade extends Model
{
    use CrudTrait;
    use SoftDeletes;
    use \Venturecraft\Revisionable\RevisionableTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'setup_grades';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['template_id', 'section_id', 'period_id', 'name', 'type','max'];
    // protected $hidden = [];
    // protected $dates = [];
    protected $dates = ['deleted_at'];
    protected $appends = ['encode_grades'];

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

    public function subject(){
        return $this->belongsTo('App\Models\SubjectManagement','subject_id')->withTrashed();
    }

    public function schoolYear ()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function template ()
    {
        return $this->belongsTo("App\Models\GradeTemplate")->withTrashed();
    }

    public function section ()
    {
        return $this->belongsTo("App\Models\SectionManagement");
    }

    public function period ()
    {
        return $this->belongsTo("App\Models\Period");
    }

    public function setupGradeItems ()
    {
        return $this->hasMany(SetupGradeItem::class, 'setup_grade_id');
    }

    public function teacher ()
    {
        return $this->belongsTo(User::class);
    }

    public function approveBy ()
    {
        return $this->belongsTo(User::class, 'approved_by');
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

    public function getLevelAttribute ()
    {
        $section = $this->section()->with('level')->first();
        if($section !== null) {
            return $section->level->year;
        }
        return '-';
    }

    public function getIsApprovedAttribute ()
    {
        if($this->attributes['is_approved'] === null) {
            return 'Pending';
        } else if ($this->attributes['is_approved'] === 0) {
            return 'Rejected';
        } else {
            return 'Approved';
        }
    }

    public function getEncodeGradesAttribute ()
    {
        return EncodeGrade::where([
                        'subject_id' => $this->subject_id,
                        'section_id' => $this->section_id,
                        'period_id'  => $this->period_id,
                        'teacher_id' => $this->teacher_id,
                    ])->get();
    }

    public function getEncodingStatusAttribute ()
    {
        $current_date = Carbon::now();
        $section = $this->section()->first();
        if($section) {
            $level =  $section->level;
            if($level) {
                $encodingSchedule   =   EncodeGradeSchedule::where('school_year_id', $this->school_year_id)
                                            ->where('department_id', $level->department_id)
                                            ->where('term_type', $this->term_type)
                                            ->first();
                if($encodingSchedule) {
                    if($encodingSchedule->start_at <= $current_date && $encodingSchedule->end_at >= $current_date) {
                        return 1;
                    }
                }
            }
        }
        return 0;
    }

    public function getAllowedEmployeeAttribute ()
    {
        $current_date = Carbon::now();
        $encodingSchedule   =   EmployeeEncodeGradeSchedule::where('school_year_id', $this->school_year_id)
                                            ->where('term_type', $this->term_type)
                                            ->where('section_id', $this->section_id)
                                            ->where('subject_id', $this->subject_id)
                                            ->where('start_at', '<=', $current_date)
                                            ->where('end_at', '>=', $current_date)
                                            ->get();
        return $encodingSchedule->pluck('employee_id');
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
