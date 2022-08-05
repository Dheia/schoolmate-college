<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use PDF;
use Carbon\Carbon;
use App\Models\Setting;

class SendMailablePaymentReminder extends Mailable
{
    use Queueable, SerializesModels;

    private $enrollment;
    private $tuition;
    private $payment_histories;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($enrollment, $tuition, $payment_schemes, $payment_snake)
    {
        $this->tuition              =   $tuition;
        $this->enrollment           =   $enrollment;
        $this->payment_schemes      =   $payment_schemes;
        $this->payment_snake        =   $payment_snake;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $date                       = Carbon::now()->addDay(10)->toDateString();

        $data["tuition"]            = $this->tuition;
        $data["enrollment"]         = $this->enrollment;
        $data["payment_schemes"]    = $this->payment_schemes;
        $data['payment_snake']      = $this->payment_snake;

        $data["schoolLogo"]         = $schoolLogo       = Setting::where('key', 'schoollogo')->first()->value;
        $data["schoolName"]         = $schoolName       = Setting::where('key', 'schoolname')->first()->value;
        $data["schoolAddress"]      = $schoolAddress    = Setting::where('key', 'schooladdress')->first()->value;
        $data["schoolAbbr"]         = $schoolAbbr       = Setting::where('key', 'schoolabbr')->first()->value;

        $this->schoolAbbr           = $schoolAbbr       = Setting::where('key', 'schoolabbr')->first()->value;
        // $pdf = \App::make('dompdf.wrapper');
        // $pdf->loadHTML( view('studentAccount.mail_payment_reminder', $data) );

        return $this->subject('SchoolMATE ' .  $this->$schoolAbbr .' - Payment Reminder for ' . date('F d, Y', strtotime($date)))
                    // ->attachData($pdf->stream(), "Statement of Accounts.pdf")
                    ->view('studentAccount.mail_payment_reminder_v2')
                    ->with([
                        'tuition'           => $this->tuition, 
                        'enrollment'        => $this->enrollment, 
                        'payment_schemes'   => $this->payment_schemes,
                        'payment_snake'     =>  $this->payment_snake,
                        'school_logo'       =>  $schoolLogo,
                        'school_name'       =>  $schoolName,
                        'school_address'    =>  $schoolAddress,
                        'schoolAbbr'        =>  $schoolAbbr
                    ]);
    }
}
