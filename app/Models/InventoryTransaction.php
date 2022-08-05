<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Student;
use App\Models\Employee;

class InventoryTransaction extends Model
{
    use CrudTrait;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'inventory_transactions';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['invoice_no', 'items', 'rfid', 'client_type', 'user_type', 'total', 'amount_tendered'];
    // protected $hidden = [];
    // protected $dates = [];
    protected $dates = ['deleted_at'];
    protected $appends = ['user'];

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

    public function getUserAttribute()
    {
        $rfid     = Rfid::where('rfid', $this->rfid)->first();
        $userType = 'unknown';
        $name     = 'unknown';

        if($rfid !== null)
        {   
            if($rfid->user_type == 'employee' )
            {
                $userType = 'Employee';
                $name     = Employee::where('employee_id', $rfid->studentnumber)->first()->full_name ?? 'unknown';
            } 
            else
            {
                $userType = 'Student';
                $name     = Student::where('studentnumber', $rfid->rfid)->first()->full_name ?? 'unknown';
            }
        }

        $data = [
            'user_type' => $userType,
            'full_name' => $name
        ];
        return (object)$data;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
