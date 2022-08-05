<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class PayrollRun extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'payroll_runs';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['payroll_id', 'date_from', 'date_to', 'status', 'run_by'];
    // protected $hidden = [];
    protected $appends = ['total_net_pay'];
    protected $dates = ['date_from', 'date_to'];

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

    public function payrollRunItems ()
    {
        return $this->hasMany(PayrollRunItem::class, 'payroll_run_id');
    }

    public function user ()
    {
        return $this->belongsTo(User::class, 'run_by');
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

    public function getTotalNetPayAttribute ()
    {
        $items = $this->payrollRunItems()->get();
        if($items) {
            $sum = $items->map(function ($value, $key) {
                $payroll = json_decode($value->payroll);
                return $payroll->items ? $payroll->items->net_pay : null;
            });
            return collect($sum)->sum();
        }
        return 0;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
