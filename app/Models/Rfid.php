<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Student;
use App\Models\Employee;


class Rfid extends Model
{
    use CrudTrait;
    use \Awobaz\Compoships\Compoships;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'rfids';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['studentnumber','rfid', 'school_year_id', 'user_type', 'is_active', 'start_date', 'end_date'];
    // protected $hidden = [];
    // protected $dates = [];
    protected $dates = ['deleted_at', 'start_date', 'end_date'];

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

    public function student () {
        return $this->belongsTo(Student::class, 'studentnumber', 'studentnumber');
    }

    public function enrollment() {
        return $this->belongsTo(Enrollment::class, 'studentnumber', 'studentnumber');
    }


    public function studentRfid () {
        return $this->belongsTo(Student::class, 'studentnumber', 'studentnumber');
    }

    // public function logs(){
    //     return $this->belongsTo(TurnstileLog::class, 'rfid', 'rfid');
    // }

    public function turnstilelogs () {
        return $this->hasMany(TurnstileLog::class, 'rfid', 'rfid');
    }

    public function schoolYear ()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function employee ()
    {
        return $this->belongsTo(Employee::class, 'studentnumber', 'employee_id');
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

    public function getFullNameAttribute(){
        if($this->user_type === 'student') {
            $student = $this->student()->first();
            if($student !== null) {
                return strtoupper($student->fullname) ?? null;
            }
        }

        if($this->user_type === 'employee') {
            $employee = $this->employee()->first();
            if($employee !== null) {
                return strtoupper($employee->fullname) ?? null;
            }
        }
        
        if($this->user_type === 'visitor') {
            return 'VISITOR PASS';
        }
        
        return 'Unknown';
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}