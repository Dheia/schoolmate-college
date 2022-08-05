<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemOrder extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'item_orders';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['code', 'studentnumber', 'orders', 'item_inventory_id', 'quantity', 'total_price', 'approved'];
    // protected $hidden = [];
    // protected $dates = [];
    protected $appends = ['total_price', 'student_fullname', 'position'];
    protected $casts = [
        'orders' => 'array',
    ];

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
    public function student() {
        return $this->belongsTo('App\Models\Student', 'studentnumber', 'studentnumber');
    }
     public function employee() {
        return $this->belongsTo(Employee::class);
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
    public function getIDNumberAttribute(){
        if($this->studentnumber ?? ''){
            return $this->studentnumber;
        }
        else if ($this->employee_id ?? ''){
            $employee = $this->employee()->first();
            return $employee->employee_id;
        }

    }
    public function getTotalPriceAttribute(){
        $orders = collect($this->orders);
        $totalPrice = 0;
        $orderPrice = 0;
        foreach($orders as $order) {
            $item = ItemInventory::where('id', $order['item_inventory_id'])->first();
            $orderPrice = $item->sale_price * $order['quantity'];

            $totalPrice +=  $orderPrice;
        }
        return number_format((float)$totalPrice, 2, '.', '');
    }

    public function getStudentFullnameAttribute(){
        if($this->studentnumber ?? ''){
            $student = Student::where('studentnumber', $this->studentnumber)
                                ->with('schoolYear')
                                ->with('yearManagement')
                                ->first();
            return $student->fullname;
        }
        $employee = $this->employee()->first();
        return $employee->full_name;
    }

    public function getYearAttribute(){
        if($this->studentnumber ?? ''){
            $student = Student::where('studentnumber', $this->studentnumber)
                                ->with('schoolYear')
                                ->with('yearManagement')
                                ->first();
            return $student->current_level;
        }
        return '-';

    }

    public function getDepartmentAttribute(){
        if($this->studentnumber ?? ''){
            $student = Student::where('studentnumber', $this->studentnumber)
                                ->with('schoolYear')
                                ->with('yearManagement')
                                ->first();
            return $student->department_name;
        }
        return '-';
    }

    public function getTrackAttribute(){
        if($this->studentnumber ?? ''){
            $student = Student::where('studentnumber', $this->studentnumber)
                                ->with('schoolYear')
                                ->with('yearManagement')
                                ->first();
            return $student->track_name;
        }
        return '-';
    }
    public function getStatusAttribute(){
        if($this->pickup == 1){
            return 'Picked up';
        }
        else{
            return 'For Pick up';
        }
    }
    public function getPositionAttribute(){
        if($this->employee_id ?? ''){
            $employee = $this->employee()->first();
            return $employee->position;
        }
        return '-';
    }
}
