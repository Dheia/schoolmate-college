<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class StudentCredential extends Authenticatable
{
    use HasApiTokens;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'student_credentials';
    protected $fillable = [
       'studentnumber', 'password',
    ];
    protected $appends = [
        'full_name',
        'private_mobile_beams',
        'private_web_beams'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    // ];

    /**
     * The channels the user receives notification broadcasts on.
     *
     * @return string
     */
    public function receivesBroadcastNotificationsOn()
    {
        return env('SCHOOL_ID') . '.App.StudentCredential.' . $this->id;
    }

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function findForPassport($username) {
        return $this->where('studentnumber', $username)->first();
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function student ()
    {
        return $this->belongsTo("App\Models\Student", "studentnumber", "studentnumber");
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */
    public function getFullNameAttribute ()
    {
        $student = $this->student()->first();
        return $student !== null ? $student->fullname : $this->studentnumber;
    }

    public function getPrivateMobileBeamsAttribute()
    {
        return "mob-" . env('SCHOOL_ID') . "-student-" . $this->studentnumber;
    }

    public function getPrivateWebBeamsAttribute()
    {
        return "web-" . env('SCHOOL_ID') . "-student-" . $this->id;
    }
}
