<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class EmployeeMandatoryPagIbig extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'employee_mandatory_pag_ibigs';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'name',
        'computation_salary_table',
        'active'
    ];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public static function getMonthlyContributionBySalary ($salary)
    {
        $salary = (double)$salary;
        $entity = EmployeeMandatoryPagIbig::where('active', 1)->first();
        $contribution_table = json_decode($entity->computation_salary_table);

        foreach ($contribution_table as $key => $value) {
            if($salary >= (double)$value->salary_per_month_min && $salary <= (double)$value->salary_per_month_max) {
                return (double)$value->employee_share + (double)$value->employer_share;
            }
        }
        return 0;
    }


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

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
