<?php

namespace App\Http\Middleware;

use Closure;

class FirstTimeLogin
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
        if(!backpack_auth()->user()->first_time_login) {
            \Alert::warning("Please You Need To Change Your Password For First Time Login")->flash();
            return redirect('admin/change-password');
        }

        return $next($request);
    }
}
