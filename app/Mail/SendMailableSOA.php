<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use PDF;
use App\Models\Setting;

class SendMailableSOA extends Mailable
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
    public function __construct($enrollment, $tuition, $payment_histories)
    {
        $this->tuition              =   $tuition;
        $this->enrollment           =   $enrollment;
        $this->payment_histories    =   $payment_histories;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data["tuition"]            = $this->tuition;
        $data["enrollment"]         = $this->enrollment;
        $data["payment_histories"]  = $this->payment_histories;

        $data["schoolLogo"]         = $schoolLogo = Setting::where('key', 'schoollogo')->first()->value;
        $data["schoolName"]         = $schoolName = Setting::where('key', 'schoolname')->first()->value;
        $data["schoolAddress"]      = $schoolAddress = Setting::where('key', 'schooladdress')->first()->value;
        $data["schoolAbbr"]         = $schoolAbbr = Setting::where('key', 'schoolabbr')->first()->value;

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML( view('studentAccount.mail_soa_pdf', $data) );

        return $this->subject('SchoolMATE ' . $this->$schoolAbbr .' - Statement of Accounts')
                    ->attachData($pdf->stream(), "Statement of Accounts.pdf")
                    ->view('studentAccount.mail_soa_v2')
                    ->with([
                        'tuition'           => $this->tuition, 
                        'enrollment'        => $this->enrollment, 
                        'payment_histories' => $this->payment_histories,
                        'schoolLogo'        =>  $schoolLogo,
                        'schoolName'        =>  $schoolName,
                        'schoolAddress'     =>  $schoolAddress,
                        'schoolAbbr'        =>  $schoolAbbr
                    ]);
    }
}
