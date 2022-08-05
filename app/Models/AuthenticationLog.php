<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class AuthenticationLog extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'authentication_logs';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];
    protected $appends = ['full_name', 'login_type', 'duration', 'week_day'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public static function getAuthenticationLogs($user_id, $user_type, $period, $start_date, $end_date)
    {
        switch ($period) {
            case 'today':

                $start_date         =   Carbon::today();
                $end_date           =   Carbon::today();
                $authLogs           =   AuthenticationLog::where('user_type', $user_type)
                                            ->where('user_id', $user_id)
                                            ->whereDate('created_at', '>=' , $start_date)
                                            ->whereDate('created_at', '<=' , $end_date)
                                            ->get();

                break;

            case 'this_week':

                $start_date         =   Carbon::now()->startOfWeek();
                $end_date           =   Carbon::now()->endOfWeek();
                $authLogs           =   AuthenticationLog::where('user_type', $user_type)
                                            ->where('user_id', $user_id)
                                            ->whereDate('created_at', '>=' , $start_date)
                                            ->whereDate('created_at', '<=' , $end_date)
                                            ->get();

                break;

            case 'this_month':

                $start_date         =   Carbon::now()->startOfMonth();
                $end_date           =   Carbon::now()->endOfMonth();
                $authLogs           =   AuthenticationLog::where('user_type', $user_type)
                                            ->where('user_id', $user_id)
                                            ->whereDate('created_at', '>=' , $start_date)
                                            ->whereDate('created_at', '<=' , $end_date)
                                            ->get();

                break;

            case 'custom':

                if( $start_date == null && $end_date == null ) {
                    return  ["status" => "ERROR", "message" => "No Selected Date"];
                }

                if( self::validateDate($start_date) == false && self::validateDate($end_date) == false) {
                    return  ["status" => "ERROR", "message" => "Invalid Date Format"];
                }

                $start_date         =   Carbon::parse($start_date);
                $end_date           =   Carbon::parse($end_date);
                $authLogs           =   AuthenticationLog::where('user_type', $user_type)
                                            ->where('user_id', $user_id)
                                            ->whereDate('created_at', '>=' , $start_date)
                                            ->whereDate('created_at', '<=' , $end_date)
                                            ->get();

                break;
            
            default: 
                return collect([]);
                break;
        }

        return isset($authLogs) ? $authLogs : collect([]);
    }

    public static function validateDate($date, $format = 'Y-m-d')
    {
        $d = new \DateTime();
        $d = $d->createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    // public function user ()
    // {
    //     return $this->belongsTo(User::class);
    // }
    public function user ()
    {
        return $this->morphTo();
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
    public function getFullNameAttribute()
    {
        $user = $this->user()->first();
        if($user)
        {
            return $user->prefix ? $user->prefix . '. ' .  $user->full_name : $user->full_name;
        }
        return 'Unknown User';
    }

    public function getLoginTypeAttribute()
    {
        if($this->user_type == 'App\User')
        {
            return 'Admin';
        }
        else if($this->user_type == 'App\StudentCredential' || $this->user_type == 'App\Models\Student')
        {
            return 'Student';
        }
        else if($this->user_type == 'App\ParentCredential' || $this->user_type == 'App\Models\ParentUser')
        {
            return 'Parent';
        }
        else
        {
            return 'Unknown User';
        }
    }

    public function getDurationAttribute()
    {
        $duration   = null;
        if($this->login_at !== null && $this->logout_at !== null)
        {
            $login_at       = Carbon::parse($this->login_at);
            $logout_at      = Carbon::parse($this->logout_at);

            $diffInHours    = $logout_at->diffInHours($login_at);
            $diffInMinutes  = $logout_at->diffInMinutes($login_at);
            $diffInSeconds  = $logout_at->diffInSeconds($login_at);
            $diff           = $logout_at->diff($login_at);

            $duration['diffInHours']   = $diffInHours;
            $duration['diffInMinutes'] = $diffInMinutes;
            $duration['diffInSeconds'] = $diffInSeconds;
            $duration['diff'] = $diff;
        }
        return $duration;
    }

    public function getWeekDayAttribute()
    {
        return Carbon::parse($this->created_at)->format('l');
    }


    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
