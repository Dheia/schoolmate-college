<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Student;
use App\Models\Tuition;
use App\Models\Enrollment;
use App\Models\SchoolYear;
use App\Models\PaymentHistory;
use App\Models\EmailLog;

use PDF;
use Mail;
use Carbon\Carbon;
use App\Mail\SendMailablePaymentReminder;

class MailPaymentReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:paymentReminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mail a Payment Reminder to a student / parent';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // $date           =   '2020-07-03';
        $date           =   Carbon::now()->addDays(10)->toDateString();

        try {
            $tuitions       =   Tuition::whereJsonContains('payment_scheme', ['scheme_date' => $date])->get();
            $enrollments    =   Enrollment::whereIn('tuition_id',  $tuitions->pluck('id'))->get();

            if( count($enrollments) > 0 ) {
                foreach ($enrollments as $key => $enrollment) {

                    $student            = $enrollment->student;
                    $tuition            = $enrollment->tuition;
                    $payment_snake      = $enrollment->commitmentPayment->snake . '_amount';
                    $payment_schemes    = collect($tuition->payment_scheme)->where('scheme_date', $date);

                    if($student && $tuition) {
                    
                        $send_to = [];

                        // Send Email To Father's Email
                        if($student->father_email) {
                            $send_to[] = $student->father_email;
                        }

                        // Send Email To Mother's Email
                        if($student->mother_email) {
                            $send_to[] = $student->mother_email;
                        }

                        // Send Email To Legal Guardian's Email
                        if($student->legal_guardian_email) {
                            $send_to[] = $student->legal_guardian_email;
                        }

                        // Send Email To Emergency Contact Email
                        if($student->emergency_email) {
                            $send_to[] = $student->emergency_email;
                        }

                        if( count($send_to) > 0 && count($payment_schemes) > 0 ) {
                            // ini_set('max_execution_time', 1200);
                            // ini_set('memory_limit', -1);

                            \Log::info([
                                'TITLE'    => 'Payment Reminder', 
                                'DATE'     => date('F d, Y', strtotime($date)),
                                'ENROLLMENT ID' => $enrollment->id,
                                'TUITION ID'    => $tuition->id,
                            ]);

                            Mail::to('jmanalo@tigernethost.com')->send(new SendMailablePaymentReminder($enrollment, $tuition, $payment_schemes, $payment_snake));
                            
                            $emailLog = EmailLog::create([
                                'subject'     => 'Payment Reminder for ' . date('F d, Y', strtotime($date)),
                                'description' => 'Enrollment ID ' . $enrollment->id . ' - Payment Reminder for ' . date('F d, Y', strtotime($date)) .  ' has been sent successfully.',
                                'receiver'    => implode ( $send_to, ', ' ),
                                'status'      => 'success',
                            ]);
                            // $emailLog = new EmailLog;
                            // $emailLog->subject      = 'Payment Reminder for ' . date('F d, Y', strtotime($date));
                            // $emailLog->description  = 'Enrollment ID ' . $enrollment->id . ' - Payment Reminder for ' . date('F d, Y', strtotime($date)) .  ' has been sent successfully.';
                            // $emailLog->receiver     = implode ( $send_to, ', ' );
                            // $emailLog->status       = 'success';
                            // $emailLog->save();
                        }
                    }
                }
            }
        }
        catch (Exception $e) {

            \Log::info([
                'TITLE'    => 'Payment Reminder Error', 
                'DATE'     => date('F d, Y', strtotime($date)),
                'MESSAGE'  => $e
            ]);

            // $emailLog = new EmailLog;
            // $emailLog->subject      = 'Payment Reminder for ' . date('F d, Y', strtotime($date));
            // $emailLog->description  = 'Enrollment ID ' . $enrollment->id . ' - ' . $e->getMessage();
            // $emailLog->receiver     = implode ( $send_to, ', ' );
            // $emailLog->status       = 'error';
            // $emailLog->save();

        }
            

    }
}
