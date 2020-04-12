<?php

namespace App\Http\Middleware;

use Closure;

class UnknownStatus
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
        if (!$request->user()->status) {
            return redirect('setstatus');
        }
        
        return $next($request);
    }
}
