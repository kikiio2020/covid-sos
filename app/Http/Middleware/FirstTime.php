<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;

class FirstTime
{
    private const CACHE_KEY_IS_FIRST_TIME = 'is_first_time';
    private const CACHE_KEY_SHOW_WELCOME = 'show_welcome';
    private const SECONDS_FOR_14_DAYS = 1209600;
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //Add show_welcome only when here the first time
        if (!Cache::get(self::CACHE_KEY_IS_FIRST_TIME) && !Cache::get(self::CACHE_KEY_SHOW_WELCOME)) {
            Cache::add(self::CACHE_KEY_SHOW_WELCOME, true, self::SECONDS_FOR_14_DAYS);
        }
        Cache::add(self::CACHE_KEY_IS_FIRST_TIME, true);
        
        return $next($request);
    }
}
