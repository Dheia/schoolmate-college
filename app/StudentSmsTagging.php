<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Models\Student;

class StudentSmsTagging extends Model
{
    protected $table = 'student_sms_taggings';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
	    'id',
		'access_token',
        'subscriber_id',
		'subscriber_number',
        'is_student'
	];
    protected $hidden = ['total_sms',
        'total_paid'];
    // protected $dates = [];
    protected $appends = [
        'subscribed',
        'full_name'
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

    public function student ()
    {
        return $this->belongsTo(Student::class, 'studentnumber', 'studentnumber')->where('user_type', 'student');
    }

    public function employee ()
    {
        return $this->belongsTo(Employee::class, 'studentnumber', 'employee_id')->where('user_type', 'employee');
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
      
    public function getSubscribedAttribute ()
    {
        if($this->subscriber_id !== null || $this->subscriber_id !== 0) { return "Yes"; } 
        return "No";
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
