<?php

namespace App;

use Backpack\CRUD\CrudTrait;
use Backpack\Base\app\Models\Traits\InheritsRelationsFromParentModel;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Passport\HasApiTokens;

use Illuminate\Broadcasting\PrivateChannel;

class User extends Authenticatable
{
    use Notifiable;
    use CrudTrait; 
    use HasRoles;
    use HasApiTokens;
    use InheritsRelationsFromParentModel;

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $guard_name = 'web';
    protected $fillable = [
        'name', 'email', 'password','employee_id'
    ];
    protected $appends = ['full_name'];

    /**
     * The channels the user receives notification broadcasts on.
     *
     * @return string
     */
    public function receivesBroadcastNotificationsOn()
    {
        return env('SCHOOL_ID') . '.App.User.' . $this->id;
    }


    public function employee () 
    {
        return $this->belongsTo('App\Models\Employee','employee_id');
    }

    public function announcementRead() {
        return $this->belongsTo(AnnouncementRead::class);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */
    public function getFullNameAttribute ()
    {
        $employee = $this->employee()->first();
        return $employee !== null ? $employee->prefix . '. ' .  $employee->full_name : $this->name;
    }



    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getFirstNameAttribute ()
    {
        return $this->name;
    }
}
