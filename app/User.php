<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use App\Traits\ModelCacheTrait;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, ModelCacheTrait;

    public const NEARBY_CACHE_DURATION = 86400; //24 hours
    public const CACHE_KEY_NEARBY = 'nearby';
    
    public const HOME_TAB_INDEX_DURATION = 604800; //one week
    public const CACHE_KEY_HOME_TAB_INDEX = 'hometabindex';
    
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
    
    public function ask()
    {
        return $this->hasMany(Ask::class, 'user_id');
    }

    public function pledges()
    {
        return $this->hasMany(Ask::class, 'responded_by');
    }
    
    public function sos()
    {
        return $this->hasMany(Sos::class, 'created_by');
    }
    
    public function getHomeTabIndexCache()
    {
        return $this->getCache(self::CACHE_KEY_HOME_TAB_INDEX, 0);
    }
    
    public function putHomeTabIndexCache($index)
    {
        return $this->putCache(self::CACHE_KEY_HOME_TAB_INDEX, $index, self::HOME_TAB_INDEX_DURATION);
    }
    
    public function getNearbyCache(): ?Collection 
    {
        return unserialize(
            $this->getCache(self::CACHE_KEY_NEARBY)
        ) ?: null;
    }
    
    public function putNearbyCache(Collection $sosCollection): bool 
    {
        return $this->putCache(
            self::CACHE_KEY_NEARBY, serialize($sosCollection), self::NEARBY_CACHE_DURATION
        );
    }
    
    public function clearNearbyCache(): bool
    {
        return self::clearNearbyCacheById($this->id);
    }
    
    public static function clearNearbyCacheById(int $userId): bool
    {
        return self::forgetCacheById(self::CACHE_KEY_NEARBY, $userId);
    }
    
    public function getNearbyResult($limit = 10): Collection
    {
        $result = $this->getNearbyCache();
        
        if ($result) {
            return $result;
        }
        
        $asksQuery = \DB::table('asks')
        ->leftJoin('users as creator', 'asks.user_id', '=', 'creator.id')
        ->join('users as responder', function ($join) {
            $join
            ->on('creator.id', '<>', 'responder.id')
            ->where('responder.id' , '=', $this->id);
        })
        ->leftJoin('sos', 'sos.id', '=', 'asks.sos_id')
        ->select([
            'asks.id',
            'asks.user_id',
            'asks.sos_id',
            'asks.needed_by',
            'asks.special_instruction',
            'asks.status',
            'asks.responded_by',
            'asks.chat',
            'asks.receipt_image',
            'asks.user_approved',
            'asks.responder_approved',
            'asks.deleted_at',
            'asks.created_at',
            'asks.updated_at',
            
            'sos.name',
            'sos.description',
            'sos.vendor_name',
            'sos.vendor_address',
            'sos.compensation',
            'sos.delivery_option',
            'sos.payment_option',
            'sos.other_instruction',
            'sos.created_by',
            'sos.deleted_at',
            'sos.created_at',
            'sos.updated_at',
            
            'creator.email as creator.email',
            'creator.name as creator.name',
            'creator.address as creator.address',
        ])
        //TODO also calculate delivery distance
        ->selectRaw(
            'ST_Distance_Sphere(
                creator.longlat,
                responder.longlat
            ) AS distance'
        )
        ->where('asks.status', Ask::STATUS_PENDING)
        ->whereRaw('
            ST_Distance_Sphere(
                creator.longlat,
                responder.longlat
            ) <= 10000'
        )
        ->orderBy('asks.needed_by') //asc soonest first
        ->orderBy('asks.created_at') //asc oldest first
        ->limit($limit);
                
        $result = $asksQuery->get();
        
        $this->putNearbyCache($result);
        $this->putNearbyReverseCache($result);
        
        return $result;
    }
    
    private function putNearbyReverseCache(Collection $nearbyResult)
    {
        $nearbyResult->each(function($item) {
            Ask::putNearbyReverseCacheById($item->id, $this->id);
        });
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
