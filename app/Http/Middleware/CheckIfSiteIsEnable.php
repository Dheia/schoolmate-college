<?php

namespace App\Http\Middleware;

use Closure;

class CheckIfSiteIsEnable
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
        if (!config('app.site_enable')) {
            return response()->make(view('layouts.site_disabled'));
            // return view('layouts.site_disabled');
        }

        return $next($request);
    }
}
