<?php

namespace App\Observers;

use App\AdditionalFee;

class AdditionalFeeObserver
{
    /**
     * Handle the additional fee "created" event.
     *
     * @param  \App\AdditionalFee  $additionalFee
     * @return void
     */
    public function created(AdditionalFee $additionalFee)
    {
        \Log::info("Created Observer AdditionalFee");
        AdditionalFee::addInvoiceAdditionalFee($additionalFee);
    }

    /**
     * Handle the additional fee "updated" event.
     *
     * @param  \App\AdditionalFee  $additionalFee
     * @return void
     */
    public function updated(AdditionalFee $additionalFee)
    {
        //
    }

    /**
     * Handle the additional fee "deleted" event.
     *
     * @param  \App\AdditionalFee  $additionalFee
     * @return void
     */
    public function deleted(AdditionalFee $additionalFee)
    {
        //
    }

    /**
     * Handle the additional fee "restored" event.
     *
     * @param  \App\AdditionalFee  $additionalFee
     * @return void
     */
    public function restored(AdditionalFee $additionalFee)
    {
        //
    }

    /**
     * Handle the additional fee "force deleted" event.
     *
     * @param  \App\AdditionalFee  $additionalFee
     * @return void
     */
    public function forceDeleted(AdditionalFee $additionalFee)
    {
        //
    }
}
