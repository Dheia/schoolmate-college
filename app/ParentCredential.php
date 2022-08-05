<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class ParentCredential extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    use HasApiTokens;

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $guard_name = 'web';
    protected $fillable = [
        'parent_user_id', 
        'fullname', 
        'username', 
        'email', 
        'password',
        'is_first_time_login',
        'active'
    ];
    protected $appends = ['full_name'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The channels the user receives notification broadcasts on.
     *
     * @return string
     */
    public function receivesBroadcastNotificationsOn()
    {
        return env('SCHOOL_ID') . '.App.ParentCredential.' . $this->id;
    }

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function findForPassport($username) {
        return $this->where('username', $username)->first();
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function parent ()
    {
        return $this->belongsTo("App\Models\ParentUser", "parent_user_id");
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */
    public function getFullNameAttribute ()
    {
        $parent = $this->parent()->first();
        return $parent !== null ? $parent->fullname : 'Unknown Parent';
    }
}
