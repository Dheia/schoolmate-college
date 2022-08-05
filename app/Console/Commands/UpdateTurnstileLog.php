<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TurnstileLog;

class UpdateTurnstileLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:logoutturnstilelog';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all not logout students.';

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
        //
        TurnstileLog::where('timeout',null)->update(['is_logged_in'=>0,'timeout'=>null]);
    }
}
