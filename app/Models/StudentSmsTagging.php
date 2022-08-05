<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Student;

class StudentSmsTagging extends Model
{
    use CrudTrait;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'student_sms_taggings';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
                            'studentnumber', 
                            'user_type', 
                            'position_type', 
                            'subscriber_number', 
                            'is_registered', 
                            'total_sms', 
                            'total_paid'
                        ];
    protected $hidden = ['subscriber_id'];
    // protected $dates = [];
    protected $dates = ['deleted_at'];
    protected $appends = ['user_type_title_case'];

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

    public function students ()
    {
        return $this->hasMany('App\Models\Student', 'studentnumber', 'studentnumber');
    }

    public function student ()
    {
        return $this->belongsTo(Student::class, 'studentnumber', 'studentnumber');
    }

    public function employee ()
    {
        return $this->belongsTo(Employee::class, 'studentnumber', 'employee_id');
    }

    public function employees ()
    {
        return $this->hasMany(Employee::class, 'employee_id', 'studentnumber');
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
        if($this->user_type === 'student') {
            if($this->student()->first() !== null) {
                return $this->student()->first()->fullname;
            } else {
                return 'Unknown';
            }
        }
        return $this->employee()->first()->full_name;
    }
    
    public function getFullNameWithEmployeeIdAttribute ()
    {
        if($this->user_type === 'student') {
            if($this->student()->first() !== null) {
                return $this->student()->first()->fullname;
            } else {
                return 'Unknown';
            }
        }
        return $this->employee()->first()->employee_id . ' - ' . $this->employee()->first()->full_name;
    }

    public function getUserTypeTitleCaseAttribute ()
    {
        return title_case($this->user_type);
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
