<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use App\Models\Employee;

class Schedule extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'schedules';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'employee_no',
        'schedule_template_id'
    ];
    // protected $hidden = [];
    // protected $dates = [];
    protected $appends = ['full_name'];

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
        return $this->belongsTo("App\Models\Employee");
    }

    public function scheduleTemplate ()
    {
        return $this->belongsTo("App\Models\ScheduleTemplate");
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

    // public function getTimeAttribute($value)
    // {
    //     $time = Carbon::createFromFormat('H:i:s', $value);

    //     return $time->format('H:i');
    // }

    public function getFullNameAttribute()
    {
        $employee = Employee::where("employee_id", $this->employee_no)->first();
        return $employee->full_name;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
