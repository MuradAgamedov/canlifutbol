<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;

class StorePreviousRoute
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
        // Store the previous route name
        if ($request->session()->has('previous_route_name')) {
            $previousRouteName = $request->session()->get('previous_route_name');
        } else {
            $previousRouteName = 'none'; // Or any default value
        }

        // Store the current route name in the session
        $request->session()->put('previous_route_name', Route::currentRouteName());

        return $next($request);
    }
}
