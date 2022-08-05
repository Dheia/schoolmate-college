<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmsLog extends Model
{
    use CrudTrait;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'sms_logs';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['studentnumber', 'is_sent_entrance', 'is_sent_exit', 'message_in', 'message_out', 'delivery_report_entrance', 'delivery_report_exit'];
    // protected $hidden = [];
    // protected $dates = [];
    protected $casts = [
        'is_sent_entrance' => 'boolean'
    ];
    
    protected $dates = ['deleted_at'];


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

    public function student ()
    {
        $this->belongsTo(Student::class, 'studentnumber', 'studentnumber');
    }

    public function employee ()
    {
        $this->belongsTo(Employee::class, 'studentnumber', 'employee_id');
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
        $student = new Student;

        if($student->where('studentnumber', $this->studentnumber)->exists()) {
            return $student->where('studentnumber', $this->studentnumber)->first()->full_name;
        } else {
            $employee = new Employee;

            if($employee->where('employee_id', $this->studentnumber)->exists()) {
                return $employee->where('employee_id', $this->studentnumber)->first()->full_name;
            }
        }

        return '-';

    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
