<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\SosCollection;
use Illuminate\Support\Collection;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    public const CACHE_KEY_NEARBY = 'nearby';
    
    public const STATUS_UNKNOWN = 0;
    public const STATUS_RESPONDER = 1;
    public const STATUS_QUARANTINE = 2;
    public const STATUS_HIGHRISK = 3;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'address', 'longlat', 'postal', 'status'
    ];
    
    protected $visible = ['id', 'name', 'email', 'address', 'status', 'statuscaption'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    public function getUserName() 
    {
        return $this->name ?: $this->email;
    }
    
    public function shoplist()
    {
        return $this->hasMany('App\Shoplist', 'user_id');
    }
    
    public function sos()
    {
        return $this->hasMany('App\Sos', 'created_by');
    }
    
    public function pledged()
    {
        return $this->hasMany('App\Sos', 'responded_by');
    }
    
    public static function getNearbyCacheKey(int $userId): string
    {
        return self::CACHE_KEY_NEARBY . '_' . $userId;
    }
    
    public function getNearbyCache(): Collection {
        return unserialize(Cache::get($this->getNearbyCacheKey($this->id)));
    }
    
    public function putNearbyCache(Collection $sosCollection) {
        return Cache::put($this->getNearbyCacheKey($this->id), serialize($sosCollection));
    }
    
    public function getStatuscaptionAttribute($value)
    {
        switch ($value) {
            case self::STATUS_RESPONDER:
                return 'Responder';
                break;
            default:
                return $value;
                break;
        }
    }
    
    static public function getStatusCaptions() 
    {
        return [
            self::STATUS_UNKNOWN => [
                'caption' => 'Unknown',
                'description' => 'You have not complete the screening questions',
            ],
            self::STATUS_RESPONDER => [
                'caption' => 'Responder',
                'description' => 'You are healthy and are able to help your neighbor',
            ],
            self::STATUS_QUARANTINE => [
                'caption' => 'Self-isolating',
                'description' => 'You are currently in self-isolation',
            ],
            self::STATUS_HIGHRISK => [
                'caption' => 'Precautionary Self-isolating',
                'description' => 'You are currently in self-isolation because you are at high risk of being infected',
            ],
        ];
    }
}
