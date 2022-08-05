<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class SystemAttendance extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'system_attendances';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['user_id', 'user_type', 'time_in', 'time_out'];
    // protected $hidden = [];
    // protected $dates = [];
     protected $appends = ['duration', 'week_day'];

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
    // public function user ()
    // {
    //     return $this->belongsTo(User::class);
    // }
    public function user ()
    {
        return $this->morphTo();
    }

    public function rfid ()
    {
        return $this->belongsTo(Rfid::class);
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
    // public function getUserTypeAttribute ()
    // {
    //     $rfid = $this->rfid()->first();
    //     if($rfid) {
    //         return $rfid->user_type;
    //     } else {
    //         return 'Employee';
    //     }
    // }

    public function getDurationAttribute()
    {
        $duration   = null;
        if($this->time_in !== null && $this->time_out !== null)
        {
            $start_time = Carbon::parse($this->time_in);
            $end_time   = Carbon::parse($this->time_out);

            $diffInHours   = $end_time->diffInHours($start_time);
            $diffInMinutes = $end_time->diffInMinutes($start_time);
            $diffInSeconds = $end_time->diffInSeconds($start_time);
            $diff          = $end_time->diff($start_time);

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
