<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class EmployeeTaxManagement extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'employee_tax_managements';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['name', 'description', 'tax_table', 'active'];
    // protected $hidden = [];
    // protected $dates = [];
    protected $cast = [
        'tax_table' => 'array'
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public static function getTax ($salary)
    {
        $monthly_salary = (double)$salary;

        // Compute Monthly Salary To Annual Salary
        $annual_salary = $monthly_salary * 12;

        // Tax Table Salary Range
        $entity = EmployeeTaxManagement::where('active', 1)->first();

        if($entity) {
            $tax_table = json_decode($entity->tax_table);

            $tax_table_first_array = $tax_table[0];
            $tax_table_last_array = $tax_table[array_key_last($tax_table)];
            $tax_table_between_first_and_last_of_array = collect($tax_table)->splice(1, array_key_last($tax_table) - 1);
            
            // Tax Table First Array 
            if($annual_salary >= (double)$tax_table_first_array->salary_min && $annual_salary <= (double)$tax_table_first_array->salary_max) {
                $rate = (double)$tax_table_first_array->rate / 100;
                $amount = ( ( ($annual_salary - (double)$tax_table_first_array->excess) * $rate ) + (double)$tax_table_first_array->basic_amount ) / 12;
                return $amount;
            }

            // Tax Table Last Array
            else if ($annual_salary > (double)$tax_table_last_array->salary_min && $annual_salary <= (double)$tax_table_last_array->salary_max) {
                $rate = (double)$tax_table_last_array->rate / 100;
                $amount = ( ( ($annual_salary - (double)$tax_table_last_array->excess) * $rate ) + (double)$tax_table_last_array->basic_amount ) / 12;
                return $amount;
            }

            else {
                foreach ($tax_table_between_first_and_last_of_array as $value) {
                    if($annual_salary > (double)$value->salary_min && $annual_salary <= (double)$value->salary_max) {
                        $rate = (double)$value->rate / 100;
                        $amount = ( ( ($annual_salary - (double)$value->excess) * $rate ) + (double)$value->basic_amount ) / 12;
                        return $amount;
                    }
                }
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
