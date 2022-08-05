<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Rfid;

class MarkedAsIn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:markedasin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark all client to in';

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

        $data = Rfid::with(['student.level'])->get();
        
        foreach($data as $item){
           
            if($item){
                // dd($item->student->level);
                $array_data = [
                "studentnumber" => $item->studentnumber ?? null,
                "is_enrolled" => $this->student->is_enrolled ?? null,
                "is_active" => $item->is_active,
                "is_in" => "0", 
                "timein" => $item->student->level->time_in ?? "6:00:00",
                "timeout" => $item->student->level->time_out ?? "18:00:00"

                ];
                Redis::set($item->rfid, json_encode($array_data));
            }
            
        }
    }
}
