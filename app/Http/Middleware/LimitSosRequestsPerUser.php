<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use App\SosRequest;
use Illuminate\Http\Response;

class LimitSosRequestsPerUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, int $maxSosRequests)
    {
        $user = $request->user()->withCount(['sosRequest as outstanding_sos_requests' => function (Builder $query) {
            $query->where('status', '<>', SosRequest::STATUS_COMPLETED);
        }])->first();
        if ($user->outstanding_sos_requests >= $maxSosRequests) {
            abort(Response::HTTP_FORBIDDEN, 'max_sos_request_reached');
        }
        
        return $next($request);
    }
}
