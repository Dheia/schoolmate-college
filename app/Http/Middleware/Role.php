<?php

namespace App\Http\Middleware;

use Closure;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ... $roles)
    {

        $user = backpack_auth()->user();

        foreach($roles as $role) {

            // Check if user has the role
            if($user->hasRole($role)) {
                // return $next($request);
            } else {
                \Alert::warning("You don't have necessary permissions to see this page.")->flash();
                abort(403);
            }

        }

        return $next($request);
    }
}
