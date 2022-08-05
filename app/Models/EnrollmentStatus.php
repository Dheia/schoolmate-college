<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class EnrollmentStatus extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'enrollment_statuses';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['school_year_id', 'department_id', 'summer'];
    // protected $hidden = [];
    // protected $dates = [];
    protected $appends = ['department_name', 'school_year_name'];

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

    public function schoolYear ()
    {
        return $this->belongsTo(SchoolYear::class);
    }


    public function department ()
    {
        return $this->belongsTo(Department::class);
    }

    public function items ()
    {
        return $this->hasMany(EnrollmentStatusItem::class);
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
    public function getDepartmentNameAttribute()
    {
        return $this->department()->first()->name;
    }
    public function getSchoolYearNameAttribute()
    {
        return $this->schoolYear()->first()->schoolYear;
    }
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
