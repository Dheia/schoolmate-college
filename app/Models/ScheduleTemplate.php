<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

use Carbon\Carbon;

class ScheduleTemplate extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'schedule_templates';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = 
    [
        'name',
        'total_weekly_hours',

        'mon_timein',
        'mon_timeout',
        'lunch_break_time_start_mon',
        'lunch_break_time_end_mon',
        'lunch_break_minutes_mon',
        'rest_day_mon',
        'no_of_hours_mon',

        'tue_timein',
        'tue_timeout',
        'lunch_break_time_start_tue',
        'lunch_break_time_end_tue',
        'lunch_break_minutes_tue',
        'rest_day_tue',
        'no_of_hours_tue',

        'wed_timein',
        'wed_timeout',
        'lunch_break_time_start_wed',
        'lunch_break_time_end_wed',
        'lunch_break_minutes_wed',
        'rest_day_wed',
        'no_of_hours_wed',

        'thu_timein',
        'thu_timeout',
        'lunch_break_time_start_thu',
        'lunch_break_time_end_thu',
        'lunch_break_minutes_thu',
        'rest_day_thu',
        'no_of_hours_thu',

        'fri_timein',
        'fri_timeout',
        'lunch_break_time_start_fri',
        'lunch_break_time_end_fri',
        'lunch_break_minutes_fri',
        'rest_day_fri',
        'no_of_hours_fri',

        'sat_timein',
        'sat_timeout',
        'lunch_break_time_start_sat',
        'lunch_break_time_end_sat',
        'lunch_break_minutes_sat',
        'rest_day_sat',
        'no_of_hours_sat',

        'sun_timein',
        'sun_timeout',
        'lunch_break_time_start_sun',
        'lunch_break_time_end_sun',
        'lunch_break_minutes_sun',
        'rest_day_sun',
        'no_of_hours_sun',
    ];
    // protected $hidden = [];
    // protected $dates = [];
    protected $appends = ['applied_days', 'total_hours']; 

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

    public function getAppliedDaysAttribute ()
    { 

        $data = [];

        if( $this->mon_timein !== null && $this->mon_timeout !== null )
        {

            $sched_timein  = Carbon::parse($this->mon_timein);
            $sched_timeout = Carbon::parse($this->mon_timeout);
            $total_hours   = $sched_timein->diffInHours($sched_timeout) - 1;

            $data['mon']['total_hours'] = $total_hours;
        } 
        
        if( $this->tue_timein !== null && $this->tue_timeout !== null )
        {
            $sched_timein  = Carbon::parse($this->tue_timein);
            $sched_timeout = Carbon::parse($this->tue_timeout);
            $total_hours   = $sched_timein->diffInHours($sched_timeout) - 1;

            $data['tue']['total_hours'] = $total_hours;
        } 
        
        if( $this->wed_timein !== null && $this->wed_timeout !== null )
        {
            $sched_timein  = Carbon::parse($this->wed_timein);
            $sched_timeout = Carbon::parse($this->wed_timeout);
            $total_hours   = $sched_timein->diffInHours($sched_timeout) - 1;

            $data['wed']['total_hours'] = $total_hours;
        } 
        
        if( $this->thu_timein !== null && $this->thu_timeout !== null )
        {
            $sched_timein  = Carbon::parse($this->thu_timein);
            $sched_timeout = Carbon::parse($this->thu_timeout);
            $total_hours   = $sched_timein->diffInHours($sched_timeout) - 1;

            $data['thu']['total_hours'] = $total_hours;
        } 
        
        if( $this->fri_timein !== null && $this->fri_timeout !== null )
        {
            $sched_timein  = Carbon::parse($this->fri_timein);
            $sched_timeout = Carbon::parse($this->fri_timeout);
            $total_hours   = $sched_timein->diffInHours($sched_timeout) - 1;

            $data['fri']['total_hours'] = $total_hours;
        } 
        
        if( $this->sat_timein !== null && $this->sat_timeout !== null )
        {
            $sched_timein  = Carbon::parse($this->sat_timein);
            $sched_timeout = Carbon::parse($this->sat_timeout);
            $total_hours   = $sched_timein->diffInHours($sched_timeout) - 1;

            $data['sat']['total_hours'] = $total_hours;
        } 
        
        if( $this->sun_timein !== null && $this->sun_timeout !== null )
        {
            $sched_timein  = Carbon::parse($this->sun_timein);
            $sched_timeout = Carbon::parse($this->sun_timeout);
            $total_hours   = $sched_timein->diffInHours($sched_timeout) - 1;

            $data['sun']['total_hours'] = $total_hours;
        }

        return $data;
    }

    public function getTotalHoursAttribute ()
    {
        $weekDays = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];
        $totalHours = 0; 
        foreach ($weekDays as $value) {
            $totalHours += $this->{'no_of_hours_' . $value} ?? 0;
        }
        return $totalHours;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
