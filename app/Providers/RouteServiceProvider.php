<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';
    protected $namespace_admin = 'App\Http\Controllers\Admin';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();
        $this->mapApiV2Routes();
        $this->mapWebRoutes();

        // MODULES FOR PUSHER
        $this->mapPusherRoutes();

        // MODULES FOR NON-ADMIN
        $this->mapWebStudentPortalRoutes();
        $this->mapWebParentPortalRoutes();
        $this->mapWebKioskRoutes();
        $this->mapWebOnlinePaymentRoutes();
        $this->mapWebCafeteriaRoutes();

        // MODULES FOR ADMIN
        $this->mapWebMyPortalRoutes();
        $this->mapWebQuickbooksRoutes();
        $this->mapWebAdmissionRoutes();
        $this->mapWebEnrollmentRoutes();
        $this->mapWebAccountingRoutes();
        $this->mapWebCurriculumRoutes();
        $this->mapWebClassRoutes();
        $this->mapWebGradeRoutes();
        $this->mapWebAssetsRoutes();
        $this->mapWebCampusSecurityRoutes();
        $this->mapWebSmsRoutes();
        $this->mapWebHumanResourcesRoutes();
        $this->mapWebPayrollRoutes();
        $this->mapWebCanteenRoutes();
        $this->mapWebLibraryRoutes();
        $this->mapWebSystemRoutes();
        $this->mapWebSchoolStoreRoutes();
        $this->mapWebOnlineClassRoutes();

        $this->mapWebParentRoutes();

        // MODULES FOR COLLEGE
        $this->mapCollegeRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')->namespace($this->namespace)->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')->namespace($this->namespace)->group(base_path('routes/api.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiV2Routes()
    {
        Route::prefix('api/v2')
             ->middleware('api')->namespace($this->namespace)->group(base_path('routes/api_v2.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapPusherRoutes()
    {
        Route::middleware('web')->namespace($this->namespace)->group(base_path('routes/pusher.php'));
    }

    /**
     * Define the "college" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapCollegeRoutes()
    {
        Route::middleware('web')->namespace($this->namespace)->group(base_path('routes/backpack/college.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapWebStudentPortalRoutes ()
    {
        Route::middleware('web')->namespace($this->namespace)->group(base_path('routes/student_portal.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapWebOnlinePaymentRoutes ()
    {
        Route::middleware('web')->namespace($this->namespace)->group(base_path('routes/online_payment.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapWebCafeteriaRoutes ()
    {
        Route::middleware('web')->namespace($this->namespace)->group(base_path('routes/cafeteria.php'));
    }


    /**
     * Define the "web" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapWebKioskRoutes ()
    {
        Route::middleware('web')->namespace($this->namespace)->group(base_path('routes/kiosk.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapWebMyPortalRoutes ()
    {
        Route::middleware('web')->namespace($this->namespace_admin)->group(base_path('routes/backpack/my_portal.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapWebQuickbooksRoutes ()
    {
        Route::middleware('web')->namespace($this->namespace_admin)->group(base_path('routes/backpack/quickbooks.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapWebAdmissionRoutes()
    {
        Route::middleware('web')->namespace($this->namespace_admin)->group(base_path('routes/backpack/admission.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapWebEnrollmentRoutes()
    {
        Route::middleware('web')->namespace($this->namespace_admin)->group(base_path('routes/backpack/enrollment.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapWebAccountingRoutes()
    {
        Route::middleware('web')->namespace($this->namespace_admin)->group(base_path('routes/backpack/accounting.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapWebCurriculumRoutes()
    {
        Route::middleware('web')->namespace($this->namespace_admin)->group(base_path('routes/backpack/curriculum.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapWebClassRoutes()
    {
        Route::middleware('web')->namespace($this->namespace_admin)->group(base_path('routes/backpack/class.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapWebGradeRoutes()
    {
        Route::middleware('web')->namespace($this->namespace_admin)->group(base_path('routes/backpack/grade.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapWebAssetsRoutes()
    {
        Route::middleware('web')->namespace($this->namespace_admin)->group(base_path('routes/backpack/assets.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapWebCampusSecurityRoutes()
    {
        Route::middleware('web')->namespace($this->namespace_admin)->group(base_path('routes/backpack/campus_security.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapWebSmsRoutes()
    {
        Route::middleware('web')->namespace($this->namespace_admin)->group(base_path('routes/backpack/sms.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapWebHumanResourcesRoutes()
    {
        Route::middleware('web')->namespace($this->namespace_admin)->group(base_path('routes/backpack/human_resources.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapWebPayrollRoutes()
    {
        Route::middleware('web')->namespace($this->namespace_admin)->group(base_path('routes/backpack/payroll.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapWebCanteenRoutes()
    {
        Route::middleware('web')->namespace($this->namespace_admin)->group(base_path('routes/backpack/canteen.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapWebLibraryRoutes()
    {
        Route::middleware('web')->namespace($this->namespace_admin)->group(base_path('routes/backpack/library.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapWebSystemRoutes()
    {
        Route::middleware('web')->namespace($this->namespace_admin)->group(base_path('routes/backpack/system.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapWebSchoolStoreRoutes()
    {
        Route::middleware('web')->namespace($this->namespace_admin)->group(base_path('routes/backpack/school_store.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapWebOnlineClassRoutes()
    {
        Route::middleware('web')->namespace($this->namespace_admin)->group(base_path('routes/backpack/online_class.php'));
    }

     /**
     * Define the "parent-user" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapWebParentRoutes()
    {
        Route::middleware('web')->namespace($this->namespace_admin)->group(base_path('routes/backpack/parent.php'));
    }

    /**
     * Define the "parent-user" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapWebParentPortalRoutes()
    {
        Route::middleware('web')->namespace($this->namespace)->group(base_path('routes/parent_portal.php'));
    }
}
