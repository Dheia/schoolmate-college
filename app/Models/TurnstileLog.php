<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Student;
use App\Models\Rfid;
use App\Models\Employee;

class TurnstileLog extends Model
{
    use CrudTrait;
    use \Awobaz\Compoships\Compoships;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'turnstile_logs';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['rfid','is_logged_in','timein','timeout'];
    // protected $hidden = [];
    // protected $dates = [];
    protected $appends = ['student_name', 'user_type'];
    protected $dates = ['deleted_at'];
    protected $casts = [
        'created_at' => 'date_format:d/m/yyyy',
    ];    

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

    public function rfids()
    {
        return $this->hasMany('App\Models\Rfid', 'rfid','rfid');
    }

    public function rfid()
    {
        return $this->belongsTo('App\Models\Rfid', 'rfid','rfid');
    }

    public function student ()
    {
        return $this->belongsTo("App\Models\Rfid")->with('student');
    }

    // public function student ()
    // {
    //     return $this->b
    //     // $d = $this->hasMany('App\Models\Rfid', 'rfid','rfid')->with('student')->get();
    //     // return $d[0]->student->full_name ?? '';
    // }

    public function studentRfid()
    {
        return $this->belongsTo('App\Models\Rfid','rfid','rfid');
    }


    public function getStudentNameAttribute ()
    {
        $rfid    = Rfid::where('rfid', $this->rfid)->first();

        if($rfid == null) { return "<span>No RFID Found</span>"; }

        $student = Student::where('studentnumber', $rfid->studentnumber)->first();
        if($student !== null) { return $student->full_name; } 
        
        $employee = Employee::where('employee_id', $rfid->studentnumber)->first();
        if($employee !== null) { return $employee->full_name; }

        return 'User Deleted';
    }

    public function getUserTypeAttribute ()
    {
        $rfid    = Rfid::where('rfid', $this->rfid)->first();

        if($rfid === null) { return "-"; }
        return $rfid->user_type;
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    // public function getStudentNameAttribute ()
    // {
    //     $rfid = App\Models\Rfid::with('student')->first();
    //     dd($rfid);
    //     return $rfid->student->firstname;
    // }

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
}
