<?php

namespace App\Observers;

use App\Models\SpecialDiscount;

class SpecialDiscountObserver
{
    /**
     * Handle the special discount "created" event.
     *
     * @param  \App\SpecialDiscount  $specialDiscount
     * @return void
     */
    public function created(SpecialDiscount $specialDiscount)
    {
        \Log::info("Created Observer SpecialDiscount");
        SpecialDiscount::addInvoiceSpecialDiscount($specialDiscount);
    }

    /**
     * Handle the special discount "updated" event.
     *
     * @param  \App\SpecialDiscount  $specialDiscount
     * @return void
     */
    public function updated(SpecialDiscount $specialDiscount)
    {
        //
    }

    /**
     * Handle the special discount "deleted" event.
     *
     * @param  \App\SpecialDiscount  $specialDiscount
     * @return void
     */
    public function deleted(SpecialDiscount $specialDiscount)
    {
        //
    }

    /**
     * Handle the special discount "restored" event.
     *
     * @param  \App\SpecialDiscount  $specialDiscount
     * @return void
     */
    public function restored(SpecialDiscount $specialDiscount)
    {
        //
    }

    /**
     * Handle the special discount "force deleted" event.
     *
     * @param  \App\SpecialDiscount  $specialDiscount
     * @return void
     */
    public function forceDeleted(SpecialDiscount $specialDiscount)
    {
        //
    }
}
