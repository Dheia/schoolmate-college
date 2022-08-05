<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class SubmittedGrade extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'encode_grades';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['teacher_id'];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public static function getTotalSubmittedGrades ()
    {
        $submittedGrades = SubmittedGrade::where('submitted', 1)->get()->count();
        return $submittedGrades;
    }

    public static function getTotalUnsubmittedGrades ()
    {
        $UnsubmittedGrades = SubmittedGrade::where('submitted', 0)->get()->count();
        return $UnsubmittedGrades;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function users ()
    {
        return $this->hasMany(User::class, 'employee_id', 'teacher_id');
    }

    public function employees ()
    {
        return $this->hasMany(Employee::class, 'id', 'teacher_id');
    }

    public function employee ()
    {
        return $this->belongsTo(Employee::class);
    }

    public function user ()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function schoolYear ()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function subject ()
    {
        return $this->belongsTo(SubjectManagement::class)->withTrashed();
    }

    public function section ()
    {
        return $this->belongsTo(SectionManagement::class);
    }

    public function period ()
    {
        return $this->belongsTo(Period::class);
    }

    public function template ()
    {
        return $this->belongsTo(GradeTemplate::class)->withTrashed();
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

    public function getFullNameAttribute ()
    {
        return $this->user()->first() ? $this->user()->first()->full_name : $this->user()->first()->name;
    }

    public function getEmployeeIdAttribute ()
    {
        return $this->user()->with('employee')->first()->employee->id;
    }

    public function getEmployeeNoAttribute ()
    {
        return $this->user()->with('employee')->first()->employee->employee_id;
    }

    public function getLevelAttribute ()
    {
        return $this->section()->with('level')->first()->level->year;
    }

    public function getNoOfSubjectsAttribute ()
    {
        $submittedGrades = SubmittedGrade::where('teacher_id', $this->teacher_id)
                            ->where('school_year_id', $this->school_year_id)
                            ->groupBy('subject_id')
                            ->get();
        return count($submittedGrades);
    }

    public function getNoOfClassesAttribute ()
    {
        $department_id = \Route::current()->parameter('department_id');
        $levels = YearManagement::where('department_id', $department_id)->get();
        $sections = SectionManagement::whereIn('level_id', $levels->pluck('id'))->get();
        $user = User::where('id', $this->teacher_id)->first();

        $teacher_subjects = TeacherSubject::where('teacher_id', $user->employee_id)
                            ->where('school_year_id', $this->school_year_id)
                            ->whereIn('section_id', $sections->pluck('id'))
                            ->get();
        return count($teacher_subjects);
    }

    public function getNoOfSectionsAttribute ()
    {
        $user = User::where('id', $this->teacher_id)->first();
        $submittedGrades = SubmittedGrade::where('teacher_id',  $user->employee_id)
                            ->where('school_year_id', $this->school_year_id)
                            ->groupBy('section_id')
                            ->get();
        return count($submittedGrades);
    }

    public function getTeacherSubmittedGradesAttribute ()
    {
        $user = User::where('id', $this->teacher_id)->first();
        $department_id = \Route::current()->parameter('department_id');
        $levels = YearManagement::where('department_id', $department_id)->get();
        $sections = SectionManagement::whereIn('level_id', $levels->pluck('id'))->get();

        $teacher_subjects = TeacherSubject::where('teacher_id', $user->employee_id)
                            ->where('school_year_id', $this->school_year_id)
                            ->whereIn('section_id', $sections->pluck('id'))
                            ->get();

        $periods = Period::where('department_id', \Route::current()->parameter('department_id'))->get();
        $submittedGrades = SubmittedGrade::where('teacher_id', $this->teacher_id)
                            ->where('school_year_id', $this->school_year_id)
                            ->whereIn('period_id', $periods->pluck('id'))
                            ->whereIn('subject_id', $teacher_subjects->pluck('subject_id'))
                            ->where('submitted', 1)
                            ->get();
        return count($submittedGrades);
    }

    public function getTeacherUnsubmittedGradesAttribute ()
    {
        $periods = Period::where('department_id', \Route::current()->parameter('department_id'))->get();
        if($periods)
        {
            $totalGrades = $this->no_of_classes * count($periods);
        }
        else
        {
            return 0;
        }
        $user = User::where('id', $this->teacher_id)->first();

        $department_id = \Route::current()->parameter('department_id');
        $levels = YearManagement::where('department_id', $department_id)->get();
        $sections = SectionManagement::whereIn('level_id', $levels->pluck('id'))->get();
        $teacher_subjects = TeacherSubject::where('teacher_id', $user->employee_id)
                            ->where('school_year_id', $this->school_year_id)
                            ->whereIn('section_id', $sections->pluck('id'))
                            ->get();
                            
        $submittedGrades = SubmittedGrade::where('teacher_id', $this->teacher_id)
                            ->where('school_year_id', $this->school_year_id)
                            ->whereIn('period_id', $periods->pluck('id'))
                            ->whereIn('subject_id', $teacher_subjects->pluck('subject_id'))
                            ->where('submitted', 1)
                            ->get()
                            ->count();
        return $totalGrades - $submittedGrades;
    }

    public function getDepartmentPeriodAttribute ()
    {
        $periods = Period::where('department_id', \Route::current()->parameter('department_id'))->get()->count();
        return $periods;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
