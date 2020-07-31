<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use App\Traits\ModelCacheTrait;
use Carbon\Carbon;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, ModelCacheTrait;

    public const NEARBY_CACHE_DURATION = 86400; //24 hours
    public const CACHE_KEY_NEARBY = 'nearby';
    
    public const HOME_TAB_INDEX_DURATION = 604800; //one week
    public const CACHE_KEY_HOME_TAB_INDEX = 'hometabindex';
    
    public const CACHE_KEY_SHOW_WELCOME = 'show_welcome';
    public const CACHE_KEY_SHOW_HUJO_INVITE = 'show_hujo_invite';
    public const ALERT_MESSAGE_DURATION = 1209600; //14 days
    
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
    
    /**
     * override
     * - Add first time alert messages cache
     */
    public static function create($data)
    {
        /** @var User $user **/
        $user = self::create($data);
        $user->putAlertMessageCache(self::CACHE_KEY_SHOW_WELCOME);
        $user->putAlertMessageCache(self::CACHE_KEY_SHOW_HUJO_INVITE);
        
        return $user;
    }
    
    public function getUserName() 
    {
        return $this->name ?: $this->email;
    }

    public function getLongLat()
    {
        return \DB::table($this->table)->selectRaw('ST_Y(longlat) AS longitude, ST_X(longlat) AS latitude')->where('id', $this->id)->first();
        
    }
    
    public function sosRequest()
    {
        return $this->hasMany(SosRequest::class, 'user_id');
    }

    public function pledges()
    {
        return $this->hasMany(SosRequest::class, 'responded_by');
    }
    
    public function sos()
    {
        return $this->hasMany(Sos::class, 'created_by');
    }
    
    public function hujoCoin(){
        return $this->hasOne(HujoCoin::class, 'user_id')->withDefault();
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
        
        $sosRequestsQuery = \DB::table('sos_requests')
        ->leftJoin('users as creator', 'sos_requests.user_id', '=', 'creator.id')
        ->leftJoin('hujo_coins', 'hujo_coins.user_id', '=', 'creator.id')
        ->join('users as responder', function ($join) {
            $join
            ->on('creator.id', '<>', 'responder.id')
            ->where('responder.id' , '=', $this->id);
        })
        ->leftJoin('sos', 'sos.id', '=', 'sos_requests.sos_id')
        ->select([
            'sos_requests.id',
            'sos_requests.user_id',
            'sos_requests.sos_id',
            'sos_requests.needed_by',
            'sos_requests.status',
            'sos_requests.responded_by',
            'sos_requests.chat',
            'sos_requests.receipt_image',
            'sos_requests.user_approved',
            'sos_requests.responder_approved',
            'sos_requests.deleted_at',
            'sos_requests.created_at',
            'sos_requests.updated_at',
            
            'sos.name',
            'sos.type',
            'sos.description',
            'sos.detail_instructions',
            'sos.created_by',
            'sos.deleted_at',
            'sos.created_at',
            'sos.updated_at',
            
            'creator.email as creator.email',
            'creator.name as creator.name',
            'creator.address as creator.address',
            
            \DB::raw('if(hujo_coins.id > 0, "Y", "N") as hujo'),            
        ])
        //TODO also calculate delivery distance
        ->selectRaw(
            'ST_Distance_Sphere(
                creator.longlat,
                responder.longlat
            ) AS distance'
        )
        ->where('sos_requests.status', SosRequest::STATUS_PENDING)
        ->where('sos_requests.needed_by', '>', Carbon::now())
        ->whereNull('sos_requests.responded_by')
        ->whereRaw('
            ST_Distance_Sphere(
                creator.longlat,
                responder.longlat
            ) <= 10000'
        )
        ->orderBy('sos_requests.needed_by') //asc soonest first
        ->orderBy('sos_requests.created_at') //asc oldest first
        ->limit($limit);
                
        $result = $sosRequestsQuery->get();
        
        $this->putNearbyCache($result);
        $this->putNearbyReverseCache($result);
        
        return $result;
    }
    
    private function putNearbyReverseCache(Collection $nearbyResult)
    {
        $nearbyResult->each(function($item) {
            SosRequest::putNearbyReverseCacheById($item->id, $this->id);
        });
    }
    
    public function getAlertMessageCache(string $alert): bool
    {
        return $this->getCache($alert, false);
    }
    
    public function putAlertMessageCache(string $alert)
    {
        return $this->putCache(
            $alert, true, self::ALERT_MESSAGE_DURATION
        );
    }
    
    public function clearAlertMessageCache(string $alert)
    {
        return self::forgetCacheById($alert, $this->id);
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
