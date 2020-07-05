<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class RequestStatusCanView
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, int $status)
    {
        $sosRequest = $request->sosRequest;

        if (!$sosRequest) {
            abort(Response::HTTP_NOT_FOUND);
        }
        
        if ($status !== $sosRequest->status) {
            abort(Response::HTTP_NOT_FOUND, 'Sorry the information you are looking for is not available');
        }
        
        $userId = $request->user()->id; 
        if ($userId !== $sosRequest->responded_by && $userId !== $sosRequest->user_id) {
            abort(Response::HTTP_FORBIDDEN, 'Sorry the information you are looking for is not available');
        }
        
        return $next($request);
    }
}
