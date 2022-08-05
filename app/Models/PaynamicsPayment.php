<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaynamicsPayment extends Model
{
    use CrudTrait;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'paynamics_payments';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'school_year_id', 
        'studentnumber', 
        'enrollment_id',

        'amount', 
        'fee',

        'pay_reference',
        'raw_data',
        'initial_response',

        'email', 
        'description', 
        'payment_method_id', 

        'request_id',
        'response_id',
        'merchant_id',
        'expiry_limit',
        'direct_otc_info',
        'payment_action_info',
        'response',

        'timestamp',
        'rebill_id',
        'signature',
        'response_code',
        'response_message',
        'response_advise',
        'settlement_info_details',

        'mail_sent',
        'status'
    ];
    // protected $hidden = [];
    // protected $dates = [];
    protected $appends = [
        'full_name',
        'payment_method_name', 
        'total_payment',
        'total_published_amount',
        'total_unpublished_amount'
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

    public function student ()
    {
        return $this->belongsTo(Student::class, 'studentnumber', 'studentnumber');
    }


    public function paymentMethod ()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function schoolYear ()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function enrollment ()
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function paymentHistories ()
    {
        return $this->hasMany(PaymentHistory::class);
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
    public function getFullNameAttribute()
    {
        $student = $this->student()->first();
        return $student->firstname . ' ' . $student->middename . '. ' . $student->lastname;
    }

    public function getPaymentMethodNameAttribute()
    {
        $payment_method = $this->paymentMethod()->first();
        return $payment_method ? $payment_method->name : '-';
    }

    public function getTotalPaymentAttribute()
    {
        return number_format((double)$this->amount + (double)$this->fee, 2, '.', ', ');
    }

    public function getTotalPublishedAmountAttribute()
    {
        return $this->paymentHistories()->sum('amount');
    }

    public function getTotalUnpublishedAmountAttribute()
    {
        return number_format((double)$this->amount - (double)$this->paymentHistories()->sum('amount'), 2, '.', ', ');
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
