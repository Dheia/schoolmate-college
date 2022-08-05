<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        switch ($guard) {
            case config('backpack.base.guard'):
                return !backpack_auth()->check() ? $next($request) : redirect(backpack_url());  
                break;

            case 'student': 
                return !Auth::guard($guard)->check() ? $next($request) : redirect('student/dashboard');
                break;

            case 'parent': 
                return !Auth::guard($guard)->check() ? $next($request) : redirect('parent/dashboard');
                break;
            default:
        }
    }
}
