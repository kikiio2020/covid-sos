<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\ModelCacheTrait;
use Carbon\Carbon;
use App\Notifications\RequestExpired;

class SosRequest extends Model 
{
    use ModelCacheTrait;
    
    public const STATUS_PENDING = 0;
    public const STATUS_IN_PROGRESS = 1;
    public const STATUS_COMPLETED = 2;
    
    public const CACHE_KEY_NEARBY_REVERSE = 'nearby_reverse';
    
    protected $fillable = [
        'sos_id',
        'needed_by',
        'status',
        'responded_by',
        'chat',
        'user_approved',
        'requestor_approved',
    ];
    
    protected $attributes = [
        'chat' => '[]',
    ];
    
    /**
     * Go through all pending requests
     * - send notification to requester for each outdated ones
     * - log the outdated ones to be removed
     * - remove the outdated ones from db
     */
    static public function expireOutdatedRequests()
    {
        SosRequest::pending()->each(function($sosRequest) {
            if ($sosRequest->needed_by < Carbon::now()) {
                $sosRequest->user->notify(new RequestExpired($sosRequest));
                $logMsg = '[Request expired and removed]:' . $sosRequest->toJson();
                \Log::channel('bookkeeping')->info($logMsg);
                $sosRequest->delete();
                echo "Found expired request {$sosRequest->id} and removed.\n";
            }
        });
    }
    
    /**
     * Relations 
     */

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function responder()
    {
        return $this->belongsTo(User::class, 'responded_by');
    }
    
    public function sos()
    {
        return $this->belongsTo(Sos::class, 'sos_id');
    }
    
    /**
     * Scopes
     */
    
    public function scopeCreatedBy(Builder $query, User $user)
    {
        return $query->where('user_id', $user->id);
    }
    
    public function scopeRespondedBy(Builder $query, User $user) 
    {
        return $query->where('responded_by', $user->id);
    }
    
    public function scopePending(Builder $query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }
    
    public function scopeInProgress(Builder $query)
    {
        return $query->where('status', self::STATUS_IN_PROGRESS);
    }
    
    public function scopeCompleted(Builder $query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }
    
    
    public function getNearbyReverseCache()
    {
        return self::getNearbyReverseCacheById($this->id);
    }
    
    public static function getNearbyReverseCacheById(int $sosRequestId)
    {
        return unserialize(
            self::getCacheById(self::CACHE_KEY_NEARBY_REVERSE, $sosRequestId)
        ) ?: [];
    }
    
    public function putNearbyReverseCache(User $user, int $seconds = null): bool
    {
        return self::putNearbyReverseCacheById($this->id, $user->id, $seconds);
    }
    
    public static function putNearbyReverseCacheById(int $sosRequestId, int $userId, int $seconds = null)
    {
        $cachedValue = self::getNearbyReverseCacheById($sosRequestId);
        $cachedValue[$userId] = $userId;

        return self::putCacheById(
            self::CACHE_KEY_NEARBY_REVERSE, 
            $sosRequestId, 
            serialize($cachedValue), 
            $seconds
        );
    }
    
    public function clearNearbyReverseCache(User $user): bool
    {
        return $this->forgetCache(self::CACHE_KEY_NEARBY_REVERSE);
    }
    
}
