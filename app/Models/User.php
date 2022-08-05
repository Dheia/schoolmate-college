<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Backpack\Base\app\Models\Traits\InheritsRelationsFromParentModel;
use Spatie\Permission\Traits\HasRoles;

use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use CrudTrait;
    use \Awobaz\Compoships\Compoships;
    use SoftDeletes;
    use HasApiTokens;
    use InheritsRelationsFromParentModel;
    use HasRoles;
    use Notifiable;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'users';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['password','first_time_login'];
    protected $hidden = ['password', 'remember_token'];
    // protected $dates = [];
    protected $dates = ['deleted_at'];
    protected $appends = ['full_name', 'has_teacher_role'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public static function addTeacherRoleToUser ($user_id)
    {
        $response = [
            'error'   => true,
            'title'   => 'Error',
            'message' => 'Error Adding Teacher Role. <br> Something Went Wrong, Please Try To Reload The Page.', 
            'data'    => null
        ];
        
        $user = User::where('id', $user_id)->first();
        $role = DB::table('roles')->where('name', 'Teacher')->first();

        if(!$user) {
            $response['message'] = 'Error Adding Teacher Role. <br> User Not Found.';
            return $response;
        }

        if($role) {
            try {
                DB::table('model_has_roles')->insert([
                    'role_id'       => $role->id,
                    'model_type'    => 'App\Models\BackpackUser',
                    'model_id'      => $user_id
                ]);
                $response['error']   = false;
                $response['title']   = 'Success';
                $response['message'] = "User's Teacher Role has been added successfully.";
            } catch (Exception $e) {
                $response['title']   = 'Error';
                $response['message'] = 'Error Adding Teacher Role. <br> Something Went Wrong, Please Try To Reload The Page.';
                return $response;
            }
        } else {
            $response['message'] = 'Error Adding Teacher Role. <br> Teacher Role Not Found.';
        }
        return $response;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function systemAttendances()
    {
        return $this->morphMany(SystemAttendance::class, 'user');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function employee () 
    {
        return $this->belongsTo('App\Models\Employee','employee_id');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */
    public function getFullNameAttribute ()
    {
        $employee = $this->employee()->first();
        return $employee !== null ? $employee->prefix . ' ' .  $employee->full_name : $this->name;
    }

    public function getHasTeacherRoleAttribute ()
    {
        $role = DB::table('roles')->where('name', 'Teacher')->first();
        if($role) {
            $model_has_role =   DB::table('model_has_roles')->where('role_id', $role->id)
                                    ->where('model_type', 'App\Models\BackpackUser')
                                    ->where('model_id', $this->id)
                                    ->first();
            return $model_has_role ? 1 : 0;
        }
        return 0;
    }
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
