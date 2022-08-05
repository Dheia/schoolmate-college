<?php

namespace App\Models;

use App\User;
use Backpack\Base\app\Models\Traits\InheritsRelationsFromParentModel;
use Backpack\Base\app\Notifications\ResetPasswordNotification as ResetPasswordNotification;
use Tightenco\Parental\HasParentModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Passport\HasApiTokens;

class BackpackUser extends User
{
    use InheritsRelationsFromParentModel;
    use SoftDeletes;
    use HasApiTokens;
    use HasRoles;

    protected $table = 'users';
    protected $dates = ['deleted_at'];

    /**
     * Send the password reset notification.
     *
     * @param string $token
     *
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Get the e-mail address where password reset links are sent.
     *
     * @return string
     */
    public function getEmailForPasswordReset()
    {
        return $this->email;
    }
}
