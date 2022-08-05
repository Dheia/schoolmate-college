<?php

namespace App\Listeners;

use App\Events\PointOfSales;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GetPOS
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  PointOfSales  $event
     * @return void
     */
    public function handle(PointOfSales $event)
    {
        //
    }
}
