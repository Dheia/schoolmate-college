<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Setting;

class SendMailableOnlinePaymentReceipt extends Mailable
{
    use Queueable, SerializesModels;

    private $onlinePayment;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($onlinePayment)
    {
        $this->onlinePayment = $onlinePayment;
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

        return $this->subject('SchoolMATE ' .  $this->$schoolAbbr .' - Online Payment Receipt')
                    ->view('onlinePayment.mail_receipt_v2')
                    ->with([
                        'school_name'       => $this->school_name,
                        'school_address'    => $this->school_address,
                        'school_logo'       => $this->school_logo,
                        'schoolAbbr'        => $this->schoolAbbr, 
                        'onlinePayment'     => $this->onlinePayment
                    ]);
    }
}
