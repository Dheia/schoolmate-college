<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Backpack\CRUD\CrudTrait;

use Spatie\Activitylog\Traits\LogsActivity;

class SchoolYear extends Model
{
    use CrudTrait;
    use \Awobaz\Compoships\Compoships;
    use SoftDeletes;
    use LogsActivity;
    
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    protected $table    = 'school_years';
    protected $guarded  = ['id'];
    protected $fillable = ['schoolYear', 'start_date', 'end_date', 'isActive', 'enable_enrollment', 'sequence'];
    protected $dates    = ['deleted_at'];
    protected $casts    = ['isActive' => 'boolean'];
    protected $appends  = ['total_enrollments'];
    
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
    public function student()
    {
        return $this->belongsTo('App\Models\Student');
    }

    public function misc ()
    {
        return $this->belongsTo('App\Models\Misc', 'schoolyear_id');
    }

    // public function school_year ()
    // {
    //     return $this->belongsTo("App\Models\SchoolYear", 'year_level_id');
    // }

    public function enrollments ()
    {
         return $this->hasMany("App\Models\Enrollment");
    }

    public function enrollment_applicants ()
    {
         return $this->hasMany("App\Models\Enrollment")->where('is_applicant', 1);
    }

    public function enrollment_enrolled ()
    {
         return $this->hasMany("App\Models\Enrollment")->where('is_applicant', 0);
    }

    public function students ()
    {
         return $this->hasMany("App\Models\Student", "schoolyear");
    }

    public function enrollment_status()
    {
        return $this->hasMany('App\Models\EnrollmentStatus');
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeActive ($query)
    {
        return $query->where('isActive', 1);
    }

    public function scopeInactive ($query)
    {
        return $query->where('isActive', 0);
    }
    
    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public function getTotalEnrollmentsAttribute ()
    {
        return $this->enrollments()->count();
    }
    public function getIsActiveWithColor ()
    {
        if($this->isActive == 1)
        {
            return '<a class="btn btn-xs btn-success" >'. 'Active'.' <i class="fa fa-bars"></i></a>';
        }
        else
        {
             return '<a class="btn btn-xs btn-danger">'. 'Active'.' <i class="fa fa-bars"></i></a>';
        }
    }
}
