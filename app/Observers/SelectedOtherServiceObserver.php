<?php

namespace App\Observers;

use App\SelectedOtherService;

class SelectedOtherServiceObserver
{
    /**
     * Handle the selected other service "created" event.
     *
     * @param  \App\SelectedOtherService  $selectedOtherService
     * @return void
     */
    public function created(SelectedOtherService $selectedOtherService)
    {
        if($selectedOtherService->approved == 1) {
            \Log::info("Created Observer SelectedOtherService");
            SelectedOtherService::addInvoiceOtherService($selectedOtherService);
        }
    }

    /**
     * Handle the selected other service "updated" event.
     *
     * @param  \App\SelectedOtherService  $selectedOtherService
     * @return void
     */
    public function updated(SelectedOtherService $selectedOtherService)
    {
        //
    }

    /**
     * Handle the selected other service "deleted" event.
     *
     * @param  \App\SelectedOtherService  $selectedOtherService
     * @return void
     */
    public function deleted(SelectedOtherService $selectedOtherService)
    {
        //
    }

    /**
     * Handle the selected other service "restored" event.
     *
     * @param  \App\SelectedOtherService  $selectedOtherService
     * @return void
     */
    public function restored(SelectedOtherService $selectedOtherService)
    {
        //
    }

    /**
     * Handle the selected other service "force deleted" event.
     *
     * @param  \App\SelectedOtherService  $selectedOtherService
     * @return void
     */
    public function forceDeleted(SelectedOtherService $selectedOtherService)
    {
        //
    }
}
