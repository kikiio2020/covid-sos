<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class PledgedRequestOnly
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
        if (!$request->sosRequest->responder) {
            abort(Response::HTTP_FORBIDDEN, 'Sorry you do not have permission to this page', 'pledged_request_only');
        }
        
        return $next($request);
    }
}
