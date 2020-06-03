<?php 
namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait ModelCacheTrait {
    
    public static function getCacheKey(string $key, int $id): string 
    {
        return $key . '_' . $id;
    }

    public function getCache($key, $default = null)
    {
        return Cache::get($this->getCacheKey($key, $this->id), $default);
    }
    
    public function putCache(string $key, string $value, int $seconds)
    {
        return Cache::put($this->getCacheKey($key, $this->id), $value, $seconds);
    }
    
    public function forgetCache(string $key)
    {
        return Cache::forget($this->getCacheKey($key, $this->id));
    }
    
    public static function getCacheById(string $key, int $id)
    {
        return Cache::get(self::getCacheKey($key, $id));
    }
    
    public static function putCacheById(string $key, int $id, string $value, int $seconds = null)
    {
        return Cache::put(self::getCacheKey($key, $id), $value, $seconds);
    }
    
    public static function forgetCacheById(string $key, int $id)
    {
        return Cache::forget(self::getCacheKey($key, $id));
    }
    
}


