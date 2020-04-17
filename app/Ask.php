<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class Ask extends Model 
{
    public const STATUS_PENDING = 0;
    public const STATUS_IN_PROGRESS = 1;
    public const STATUS_COMPLETED = 2;
    
    public const CACHE_KEY_NEARBY_REVERSE = 'nearby_reverse';
    
    protected $fillable = [
        'sos_id',
        'needed_by',
        'status',
        'special_instruction',
        'responded_by',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function sos()
    {
        return $this->belongsTo(Sos::class, 'sos_id');
    }
    
    public function scopeCreatedBy(Builder $query, User $user)
    {
        return $query->where('user_id', $user->id);
    }
    
    public function scopeRespondedBy(Builder $query, User $user) 
    {
        return $query->where('responded_by', $user->id);
    }
    
    public function scopeInProgress(Builder $query)
    {
        return $query->where('status', self::STATUS_IN_PROGRESS);
    }
    
    public function scopeCompleted(Builder $query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }
    
    public static function getNearbyReverseCacheKey(int $askId): string
    {
        return self::CACHE_KEY_NEARBY_REVERSE . '_' . $askId;
    }
    
    public function getNearbyReverseCache()
    {
        return self::getNearbyReverseCacheById($this->id);
    }
    
    public static function getNearbyReverseCacheById(int $askId)
    {
        return unserialize(
            Cache::get(self::getNearbyReverseCacheKey($askId))
        ) ?: [];
    }
    
    public function putNearbyReverseCache(User $user, int $seconds = null): bool
    {
        return self::putNearbyReverseCacheById($this->id, $user->id, $seconds);
    }
    
    public static function putNearbyReverseCacheById(int $askId, int $userId, int $seconds)
    {
        $cachedValue = self::getNearbyReverseCacheById($askId);
        $cachedValue[$userId] = $userId;
        
        return Cache::put(self::getNearbyReverseCacheKey($askId), serialize($cachedValue), $seconds);
    }
    
    public function clearNearbyReverseCache(User $user): bool
    {
        return Cache::forget($this->getNearbyReverseCacheKey($this->id), $user->id);
    }
}
