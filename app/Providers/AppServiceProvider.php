<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

// OBSERVERS
use App\Observers\StudentObserver;
use App\Observers\PaymentHistoryObserver;
use App\Observers\SelectedOtherProgramObserver;
use App\Observers\SelectedOtherServiceObserver;
use App\Observers\AdditionalFeeObserver;
use App\Observers\DiscrepancyObserver;

// MODELS
use App\Models\Student;
use App\Models\SchoolYear;
use App\Models\Discrepancy;
use App\PaymentHistory;
use App\SelectedOtherProgram;
use App\SelectedOtherService;
use App\AdditionalFee;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        if(env('FORCE_HTTPS')) {
            URL::forceScheme('https');
        }

       if (!Collection::hasMacro('paginate')) {

                Collection::macro('paginate', 
                    function ($perPage = 15, $page = null, $options = []) {
                    $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
                    return (new LengthAwarePaginator(
                        $this->forPage($page, $perPage), $this->count(), $perPage, $page, $options))
                        ->withPath(request()->url());
                });

                Student::observe(StudentObserver::class);
        }

        // Student Accounts
        PaymentHistory::observe(PaymentHistoryObserver::class);
        SelectedOtherProgram::observe(SelectedOtherProgramObserver::class);
        SelectedOtherService::observe(SelectedOtherServiceObserver::class);
        AdditionalFee::observe(AdditionalFeeObserver::class);
        Discrepancy::observe(DiscrepancyObserver::class);
    }
}
