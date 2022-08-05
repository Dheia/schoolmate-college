<?php

namespace App\Observers;

use App\Models\Discrepancy;

class DiscrepancyObserver
{
    /**
     * Handle the discrepancy "created" event.
     *
     * @param  \App\Discrepancy  $discrepancy
     * @return void
     */
    public function created(Discrepancy $discrepancy)
    {
        \Log::info("Created Observer Discrepancy");
        Discrepancy::addInvoiceDiscrepancy($discrepancy);
    }

    /**
     * Handle the discrepancy "updated" event.
     *
     * @param  \App\Discrepancy  $discrepancy
     * @return void
     */
    public function updated(Discrepancy $discrepancy)
    {
        //
    }

    /**
     * Handle the discrepancy "deleted" event.
     *
     * @param  \App\Discrepancy  $discrepancy
     * @return void
     */
    public function deleted(Discrepancy $discrepancy)
    {
        //
    }

    /**
     * Handle the discrepancy "restored" event.
     *
     * @param  \App\Discrepancy  $discrepancy
     * @return void
     */
    public function restored(Discrepancy $discrepancy)
    {
        //
    }

    /**
     * Handle the discrepancy "force deleted" event.
     *
     * @param  \App\Discrepancy  $discrepancy
     * @return void
     */
    public function forceDeleted(Discrepancy $discrepancy)
    {
        //
    }
}
