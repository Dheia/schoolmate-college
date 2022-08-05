<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class EmployeeMandatoryPhilHealth extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'employee_mandatory_phil_healths';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        "monthly_basic_salary_rate",
        'active',
        "computation_salary_table",
    ];
    // protected $hidden = [];
    // protected $dates = [];

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

    public static function getMonthlyPremiumBySalary ($salary)
    {
        $salary = (double)$salary;
        $entity = EmployeeMandatoryPhilHealth::where('active', 1)->first();
        $contribution_table = json_decode($entity->computation_salary_table);

        $monthly_premium_min_extent = $contribution_table[0];
        $monthly_premium_max_extent = $contribution_table[1];
        
        if((double)$monthly_premium_min_extent->salary_min >= $salary && $salary <= (double)$monthly_premium_min_extent->salary_max) {
            return (double)$monthly_premium_min_extent->monthly_premium / 2;
        }

        // Contribution Maximum Extent
        else if ((double)$monthly_premium_max_extent->salary_min >= $salary && $salary <= (double)$monthly_premium_max_extent->salary_max) {
            return (double)$monthly_premium_max_extent->monthly_premium / 2;
        }

        // Monthly Basic Salary x rate(%)
        else {
            $monthly_premium = $salary * ( $entity->monthly_basic_salary_rate / 100 );
            return $monthly_premium / 2;
        }

        return 0;
    }


    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
