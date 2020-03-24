<?php

namespace App\Http\Middleware;

use Closure;

class RouteTest
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
        return response('中间件不通过');
        //return $next($request);
    }
}
