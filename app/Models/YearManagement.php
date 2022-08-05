<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class YearManagement extends Model
{
    use CrudTrait;
    use \Awobaz\Compoships\Compoships;
    use SoftDeletes;
    
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'year_managements';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['year', 'department_id', 'sequence', 'time_in','time_out'];

    // protected $dates = [];
    protected $dates = ['deleted_at'];
    protected $appends = ['department_name'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function section () 
    {
        return $this->belongsTo('App\Models\SectionManagement');
    }
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function misc ()
    {
        return $this->belongsTo("App\Models\Misc", 'gradeyear_id');
    }

    public function departments ()
    {
        return $this->belongsTo("App\Models\Department");
    }

    public function sections ()
    {
        return $this->hasMany("App\Models\SectionManagement", 'level_id');
    } 

    public function tuition ()
    {
        return $this->belongsTo("App\Models\Tuition");
    }

    public function enrollment ()
    {
        return $this->belongsTo("App\Models\Enrollment");
    }

    public function yearLevel ()
    {
        return $this->belongsTo("App\Models\YearManagement");
    }

    public function department ()
    {
        return $this->belongsTo("App\Models\Department");
    }

    public function tracks ()
    {
        return $this->hasMany("App\Models\TrackManagement", 'level_id');
    }

    public function courses ()
    {
        return $this->hasMany(CourseManagement::class, 'level_id');
    }

    public function departmentWithTrashed ()
    {
        return $this->department()->withTrashed();
    }

    // public function terms ()
    // {
    //     return $this->hasMany("App\Models\TermManagement", 'level_id');
    // }

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
    public function getDepartmentNameAttribute ()
    {
        $department = $this->department()->first();
        return $department ? $department->name : '-';
    }

      
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
