<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\Models\Setting;

class SendMailablePaynamicsPaymentReceipt extends Mailable
{
    use Queueable, SerializesModels;

    private $payment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($payment)
    {
        $this->payment = $payment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->school_logo       = $schoolLogo       = Setting::where('key', 'schoollogo')->first()->value;
        $this->school_name       = $schoolName       = Setting::where('key', 'schoolname')->first()->value;
        $this->school_address    = $schoolAddress    = Setting::where('key', 'schooladdress')->first()->value;
        $this->schoolAbbr        = $schoolAbbr       = Setting::where('key', 'schoolabbr')->first()->value;

        $payment = $this->payment;
        return $this->subject('SchoolMATE ' . $this->$schoolAbbr .' - Online Payment Receipt')
                    ->view('paynamics.mail_receipt_v2')
                    ->with([
                        'payment'           => $this->$payment,
                        'school_logo'       =>  $schoolLogo,
                        'school_name'       =>  $schoolName,
                        'school_address'    =>  $schoolAddress,
                        'schoolAbbr'        =>  $schoolAbbr
                    ]);
    }
}
