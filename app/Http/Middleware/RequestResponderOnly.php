<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class RequestResponderOnly
{
    /**
     * TODO
     * - Consolidate with webapi checks
     * - Response message should be codified rather than actual text
     * - For standalone pages error text can be fed in via lang
     * - For vue pages error text can be fed in through blade to component prop
     */
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->sosRequest->responder
            || $request->sosRequest->responder->id !== $request->user()->id
        ) {
            abort(Response::HTTP_FORBIDDEN, 'Sorry you do not have permission to this page');
        }
        
        return $next($request);
    }
}
