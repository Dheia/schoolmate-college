<?php

namespace App\Observers;

use App\SelectedOtherProgram;

class SelectedOtherProgramObserver
{
    /**
     * Handle the selected other program "created" event.
     *
     * @param  \App\SelectedOtherProgram  $selectedOtherProgram
     * @return void
     */
    public function created(SelectedOtherProgram $selectedOtherProgram)
    {
        if($selectedOtherProgram->approved == 1) {
            \Log::info("Created Observer SelectedOtherProgram");
            SelectedOtherProgram::addInvoiceOtherProgram($selectedOtherProgram);
        }
    }

    /**
     * Handle the selected other program "updated" event.
     *
     * @param  \App\SelectedOtherProgram  $selectedOtherProgram
     * @return void
     */
    public function updated(SelectedOtherProgram $selectedOtherProgram)
    {
        //
    }

    /**
     * Handle the selected other program "deleted" event.
     *
     * @param  \App\SelectedOtherProgram  $selectedOtherProgram
     * @return void
     */
    public function deleted(SelectedOtherProgram $selectedOtherProgram)
    {
        //
    }

    /**
     * Handle the selected other program "restored" event.
     *
     * @param  \App\SelectedOtherProgram  $selectedOtherProgram
     * @return void
     */
    public function restored(SelectedOtherProgram $selectedOtherProgram)
    {
        //
    }

    /**
     * Handle the selected other program "force deleted" event.
     *
     * @param  \App\SelectedOtherProgram  $selectedOtherProgram
     * @return void
     */
    public function forceDeleted(SelectedOtherProgram $selectedOtherProgram)
    {
        //
    }
}
