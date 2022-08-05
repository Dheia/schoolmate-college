<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Controllers\RestCurl;

class SchoolHasModules
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $moduleType)
    {
        // IF DEBUG IS FALSE, IT WILL ALLOWED ACCESS TO ROUTES BASED ON SCHOOL WHAT MODULES THEY SUBSCRIBES
        if(!env('APP_DEBUG')) {
            $url    = 'admin.schoolmate-online.com/api/school/subscription/' . env('SCHOOL_ID');
            $header = array('Content-Type: application/json');
            $modules = RestCurl::get($url, $header);
            if ($modules->status === 200) {
                    if(count($modules->data->subscriptions) > 0) {
                            // $subscriptions = $modules->data
                            $modules_list = collect($modules->data->subscriptions[0]->modules)->pluck('name')->toArray();

                            if(collect($modules_list)->contains($moduleType)) {
                                    // Check If Has Maintenance
                                    if(count($modules->data->school->maintenance) > 0)
                                    {
                                        $hasContainSlug = collect($modules->data->school->maintenance)->pluck('slug')->contains($request->getPathInfo()); 
                                        if($hasContainSlug) { 
                                            $index = array_search($request->getPathInfo(), array_column($modules->data->school->maintenance, 'slug'));
                                            $data = $modules->data->school->maintenance[$index];
                                            return response(view('maintenance', compact('data')));
                                        } else {
                                            return response(view('unsubscription'));
                                        }
                                    } else {
                                        // IF NO MAINTENANCE THIIS ROUTES THEN, ALLOW TO RUN THIS ROUTE
                                        return $next($request);
                                    }
                            } else {
                                    // RETURN UNSUBSCRIPTION
                                    return response(view('unsubscription'));
                            }
                    } else {
                            return response(view('unsubscription'));
                    }
            }
        }

        // IF DEBUG IS TRUE, IT WILL ALLOW ALL ACCESS TO ROUTES
        return $next($request);
    }
}