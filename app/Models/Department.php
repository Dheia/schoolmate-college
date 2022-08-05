<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use CrudTrait;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'departments';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['name', 'with_track', 'course'];
    // protected $hidden = [];
    // protected $dates = [];
    protected $dates    = ['deleted_at'];
    protected $appends  = ['department_term_type', 'allow_delete'];
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

    public function levels ()
    {
        return $this->hasMany("App\Models\YearManagement", 'department_id');
    }

    public function tracks ()
    {
        return $this->hasMany('App\Models\TrackManagement', 'track_id');
    }

    public function periods ()
    {
        return $this->hasMany(Period::class, 'department_id');
    }

    public function terms ()
    {
        return $this->hasMany("App\Models\TermManagement", 'department_id');
    }

    public function term ()
    {
        return $this->hasOne("App\Models\TermManagement", 'department_id');
    }
    
    public function students ()
    {
        return $this->hasMany("App\Models\Student", 'department_id');
    }

    public function enrollments ()
    {
        return $this->hasMany("App\Models\Enrollment", 'department_id');
    }

    public function enrollment_applicants ()
    {
        return $this->hasMany("App\Models\Enrollment", 'department_id')->where('is_applicant', 1);
    }

    public function enrollment_enrolled ()
    {
        return $this->hasMany("App\Models\Enrollment", 'department_id')->where('is_applicant', 0);
    }

    public function activeEnrollments ()
    {
        return $this->enrollments()->where('school_year_id', SchoolYear::active()->first()->id);
    }

    public function termWithTrashed ()
    {
        return $this->term()->withTrashed();
    }

    // public function schoolYearEnrollments($query, $date)
    // {
    //     // A scope can be dynamic and accept parameters
    //     return $query->with('enrollments', '>', $date)
    // }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeTerm ($query)
    {
        $query->with('term');
    }

    public function scopeActive ($query)
    {
        $query->where('active', 1);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */
   
    public function getDepartmentTermTypeAttribute()
    {
        $term_management = TermManagement::where('department_id', $this->id)->first();
        return $term_management ? $term_management->type : '-';
    }

    public function getTermTypeAttribute()
    {
        $term_management = TermManagement::where('department_id', $this->id)->first();
        if($term_management){
            return $term_management->type;
        }
    }

    public function getNoOfTermAttribute()
    {
        $term_management = TermManagement::where('department_id', $this->id)->first();
        if($term_management){
            return $term_management->no_of_term;
        }
    }

    public function getAllowDeleteAttribute ()
    {
        if($this->enrollments()->count() > 0)
        {
            return false;
        }
        if($this->levels()->count() > 0)
        {
            return false;
        }
        if($this->students()->count())
        {
            return false;
        }
        if( $this->periods()->count() > 0 )
        {
            return false;
        }
        return true;
    }
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

}
