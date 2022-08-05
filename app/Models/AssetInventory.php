<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use App\Models\Building;
// use Illuminate\Database\Eloquent\SoftDeletes;

class AssetInventory extends Model
{
    use CrudTrait;
    // use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'asset_inventories';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['building_id', 'name','description','serialno','remarks','condition','user_id','updated_by','room_id','items'];
    // protected $hidden = [];
    // protected $dates = [];
    // protected $dates = ['deleted_at'];
    protected $appends = ['building_name','user_name','user_update','room_name'];

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

    public function room(){
        return $this->belongsTo('App\Models\Room')->withDefault([
            'name' => 'unknown'
        ]);
    }


    public function type(){
        return $this->belongsTo('App\Models\Type');
    }

    public function trashed()
    {
        return $this->belongsTo('App\Models\Room')->withTrashed();
    }

    public function building ()
    {
        return $this->belongsTo('App\Models\Building')->withTrashed();
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function getBuildingNameAttribute(){
        $room_id = Room::where('id',$this->room_id)->first();

        if($room_id !== null) {
            $building = Building::where('id',$room_id->building_id)->first();
            if($building !== null) {
                return $building->name;
            }
        }
        return "Unknown";
    }
    public function getRoomNameAttribute(){
        $room = Room::where('id',$this->room_id)->first();

        if($room !== null) {
            if($room !== null) {
                return $room->name;
            }
        }
        return "Unknown";
    }

    public function getUserNameAttribute() {
        $fullname = User::find($this->user_id);
        return $fullname !== null ? $fullname->name : 'Unknown';
    }

    public function getUserUpdateAttribute() {
        $fullname = User::find($this->updated_by);
        return $fullname !== null ? $fullname->name : 'Unknown';
    }
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
