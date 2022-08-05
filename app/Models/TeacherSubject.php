<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeacherSubject extends Model
{
    use CrudTrait;
    use \Awobaz\Compoships\Compoships;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'teacher_subjects';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['teacher_id', 'school_year_id', 'section_id', 'subject_id', 'term_type', 'summer'];
    // protected $hidden = [];
    // protected $dates = [];
    protected $appends = ['level_name','track_name'];


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

    public function section ()
    {
        return $this->belongsTo(SectionManagement::class);
    }

    public function sections ()
    {
        return $this->hasMany(SectionManagement::class);
    }

    public function subject ()
    {
        return $this->belongsTo(SubjectManagement::class);
    }

    public function teacher ()
    {
        return $this->belongsTo(Employee::class);
    }

    public function schoolYear ()
    {
        return $this->belongsTo(SchoolYear::class);
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

    public function getCodeSubjectNameAttribute ()
    {
        $subject = $this->subject()->first();

        if($subject !== null) {
            return $subject->subject_code . ' - ' . $subject->subject_title;
        }
        return '-';
    }

    public function getLevelNameAttribute ()
    {
        $section = $this->section()->with('level')->first();

        if($section == null) {
            return '-';
        }

        $level = $section->level;

        if($level == null) {
            return '-';
        } else {
            return $level->year;
        }
    }


    public function getTrackNameAttribute ()
    {
        $section = $this->section()->with('track')->first();

        if($section == null) {
            return '-';
        }

        $track = $section->track;

        if($track == null) {
            return '-';
        } else {
            return $track->code;
        }
    }

    public function getSectionNameAttribute ()
    {
        $section = $this->section()->first();
        return $section ? $section->name : '-';
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public function getActiveOnlineClassAttribute ()
    {

        $class = OnlineClass::where([
            'school_year_id' => $this->school_year_id,
            'section_id'     => $this->section_id,
            'subject_id'     => $this->subject_id
        ])->first();

        if($class)
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

    public function getSubmittedGradesAttribute ()
    {
        $user   =   User::with('employee')->where('employee_id', $this->teacher_id)->first();
        $term   =   $this->term_type;
        if(!$term)
        {
            $encode_grades  =   EncodeGrade::with('period')
                                    ->where('teacher_id', $user->id)
                                    ->where('school_year_id', $this->school_year_id)
                                    ->where('section_id', $this->section_id)
                                    ->where('subject_id', $this->subject_id)
                                    ->where('term_type',  'Full')
                                    ->where('submitted',  1)
                                    ->get();
        }
        else{
            $encode_grades  =   EncodeGrade::with('period')
                                    ->where('teacher_id', $user->id)
                                    ->where('school_year_id', $this->school_year_id)
                                    ->where('section_id', $this->section_id)
                                    ->where('subject_id', $this->subject_id)
                                    ->where('term_type',  $this->term_type)
                                    ->where('submitted',  1)
                                    ->get();
        }
        return $encode_grades;
    }
}
