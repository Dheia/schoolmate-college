<?php

namespace App;

use Backpack\CRUD\CrudTrait;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Activitylog\Traits\LogsActivity;


class Student extends Authenticatable
{
    // use \App\Http\Controllers\RevisionableTrait;
    use Notifiable;
    use CrudTrait; 
    // use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guard_name = 'student';
    protected $fillable = [
        'studentnumber', 'password','remember_token',
    ];
    protected $appends = ['full_name'];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password'];
    protected static $logAttributes = ['*'];

    public function getFullNameAttribute()
    {
        if($this->middlename) {
            return $this->firstname . ' ' . substr($this->middlename, 0, 1) . '. ' . $this->lastname;
        }
        return $this->firstname . ' ' . $this->lastname;
    }
}
