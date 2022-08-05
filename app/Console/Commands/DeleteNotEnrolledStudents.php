<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;

use App\Models\Student;

class DeleteNotEnrolledStudents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:deleteNotEnrolledStudents';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Delete new student data that is not enrolled after 15 days.";

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
        try {
            $date =   Carbon::now()->subDays(15)->toDateString();
            // $student  Student::where('created_at', )

        }
        catch (Exception $e) {

        }
    }
}
