<?php

namespace App\Http\Middleware;

use Closure;

class WidgetException
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
        if($request->is('arrilot/*')) {
            Debugbar::disable();
        }

        return $next($request);
    }
}
