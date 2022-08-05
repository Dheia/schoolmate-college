<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class OnlineClassAttendance extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'online_class_attendances';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['user_id', 'user_type', 'online_class_id', 'time_in', 'time_out'];
    // protected $hidden = [];
    // protected $dates = [];
    protected $cast = ['created_at' => 'datetime'];
    protected $appends = ['class_code', 'subject_name', 'duration', 'week_day'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public static function getEmployeeAttendanceToday($online_class_id, $user_id) 
    {
        $currentDate        =   Carbon::now()->toDateString();
        $class_attendance   =   OnlineClassAttendance::where('online_class_id', $online_class_id)
                                                    ->where('user_id', $user_id)
                                                    ->where('user_type', 'App\Models\Employee')
                                                    ->where('created_at', '>=', $currentDate)
                                                    ->where('created_at', '<=', $currentDate . ' 23:59:59')
                                                    ->first();
        $system_attendance  =   [
            'time_in'    => null,
            'time_out'   => null,
        ];

        return $class_attendance ?? (object)$system_attendance;
    }

    public static function getStudentAttendanceToday($online_class_id, $user_id) 
    {
        $currentDate        =   Carbon::now()->toDateString();
        $class_attendance   =   OnlineClassAttendance::where('online_class_id', $online_class_id)
                                                    ->where('user_id', $user_id)
                                                    ->where('user_type', 'App\Models\Student')
                                                    ->where('created_at', '>=', $currentDate)
                                                    ->where('created_at', '<=', $currentDate . ' 23:59:59')
                                                    ->first();
        $system_attendance  =   [
            'time_in'    => null,
            'time_out'   => null,
        ];

        return $class_attendance ?? (object)$system_attendance;
    }

    public static function getClassAttendanceLogs($user_id, $user_type, $period, $start_date, $end_date)
    {
        switch ($period) {
            case 'today':

                $start_date         =   Carbon::today();
                $end_date           =   Carbon::today();
                $classAttendance    =   OnlineClassAttendance::with('onlineClass')
                                            ->where('user_type', $user_type)
                                            ->where('user_id', $user_id)
                                            ->whereDate('created_at', '>=' , $start_date)
                                            ->whereDate('created_at', '<=' , $end_date)
                                            ->get();

                break;

            case 'this_week':

                $start_date         =   Carbon::now()->startOfWeek();
                $end_date           =   Carbon::now()->endOfWeek();
                $classAttendance    =   OnlineClassAttendance::with('onlineClass')
                                            ->where('user_type', $user_type)
                                            ->where('user_id', $user_id)
                                            ->whereDate('created_at', '>=' , $start_date)
                                            ->whereDate('created_at', '<=' , $end_date)
                                            ->get();

                break;

            case 'this_month':

                $start_date         =   Carbon::now()->startOfMonth();
                $end_date           =   Carbon::now()->endOfMonth();
                $classAttendance    =   OnlineClassAttendance::with('onlineClass')
                                            ->where('user_type', $user_type)
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
                $classAttendance    =   OnlineClassAttendance::with('onlineClass')
                                            ->where('user_type', $user_type)
                                            ->where('user_id', $user_id)
                                            ->whereDate('created_at', '>=' , $start_date)
                                            ->whereDate('created_at', '<=' , $end_date)
                                            ->get();

                break;
            
            default: 
                return collect([]);
                break;
        }

        return isset($classAttendance) ? $classAttendance : collect([]);
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
    public function user ()
    {
        return $this->morphTo();
    }

    public function onlineClass ()
    {
        return $this->belongsTo(OnlineClass::class);
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
    public function getSubjectNameAttribute()
    {
        $online_class = $this->onlineClass()->first();
        return $online_class ? $online_class->subject_name : '-';
    }

    public function getClassCodeAttribute()
    {
        $online_class = $this->onlineClass()->first();
        return $online_class ? $online_class->code : '-';
    }

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
