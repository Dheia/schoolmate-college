<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class SetupGradeItem extends Model
{
    use CrudTrait;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'setup_grade_items';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['setup_grade_id', 'name', 'description', 'max'];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public static function getTree()
    {
        $grade = self::orderBy('lft')->get();
        if ($grade->count()) {
            foreach ($grade as $k => $grade_item) {
                $grade_item->children = collect([]);
                foreach ($grade as $i => $grade_subitem) {
                    if ($grade_subitem->parent_id == $grade_item->id) {
                        $grade_item->children->push($grade_subitem);
                        // remove the subitem for the first level
                        $grade = $grade->reject(function ($item) use ($grade_subitem) {
                            return $item->id == $grade_subitem->id;
                        });
                    }
                }
            }
        }
        return $grade;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function parent()
    {
        return $this->belongsTo(SetupGradeItem::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(SetupGradeItem::class, 'parent_id');
    }

    public function setupGrade ()
    {
        return $this->belongsTo(SetupGrade::class);
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

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
