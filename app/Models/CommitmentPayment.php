<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class CommitmentPayment extends Model
{
    use CrudTrait;
    use \Awobaz\Compoships\Compoships;
    use SoftDeletes;
    
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'commitment_payments';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['name', 'additional_fee', 'active'];
    // protected $hidden = [];
    // protected $dates = [];
    protected $dates = ['deleted_at'];
    protected $appends = ['slug', 'snake'];

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
    public function misc() 
    {
        return $this->belongsTo("App\Models\Misc", "commitment_payment_id");
    }
    
    public function tuition ()
    {
        return $this->belongsTo("App\Models\Tuition", "commitment_payment_id");
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeActive ($query)
    {
        $query->where('active', 1);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    public function getSlugAttribute ()
    {
        return Str::slug($this->name, '_');
    }

    public function getSnakeAttribute ()
    {
        return strtolower(Str::snake(Str::studly($this->name)));
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
