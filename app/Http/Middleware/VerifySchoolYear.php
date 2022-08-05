<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\SchoolYear;

class VerifySchoolYear
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $schoolYear = SchoolYear::active()->first();
        if(!$schoolYear) {
            \Alert::warning('No Active School Year Found')->flash();
            return redirect('admin/schoolyear');
        }
        return $next($request);
    }
}
