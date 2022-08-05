<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class SubjectMapping extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'subject_mappings';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'curriculum_id',
        'department_id',
        'level_id',
        'term_id',
        'term_type',
        'track_id',
        'course_id',
        'subjects',
    ];
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
    public function curriculum ()
    {
        return $this->belongsTo("App\Models\CurriculumManagement");
    }

    public function department ()
    {
        return $this->belongsTo("App\Models\Department");
    }

    public function level ()
    {
        return $this->belongsTo("App\Models\YearManagement");
    }

    public function term ()
    {
        return $this->belongsTo("App\Models\TermManagement");
    }

    public function track ()
    {
        return $this->belongsTo("App\Models\TrackManagement");
    }

    public function subject ()
    {
        return $this->belongsTo("App\Models\SubjectManagement");
    }

    public function course ()
    {
        return $this->belongsTo(CourseManagement::class);
    }

    public function levelAsc ()
    {
        return $this->level()->orderBy('sequence','ASC');
    }


    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeLevelOrder ($query)
    {
        return $this->level()->orderBy('sequence','ASC');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */
    public function getSubjectsAttribute ()
    {
        return json_decode($this->attributes['subjects']);
    }

     public function getLevelOrderAttribute ()
    {
        return $this->level()->orderBy('sequence', 'ASC');
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
