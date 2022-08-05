<?php

namespace App\Console\Commands;

use App\Models\OnlineClass;
use Illuminate\Console\Command;

class LogoutAllClass extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:logoutallclass';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will logout all on going classes';

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
        OnlineClass::where('ongoing',1)->update(['ongoing'=>0]);
    }
}
