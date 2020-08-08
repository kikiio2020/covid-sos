<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HujoCoin extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'user_id',
        'crypto_address',
    ];

    /**
     * Relations 
     */
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function hujoCoinTx()
    {
        return $this->hasOne(HujoCoinTx::class, 'reference_id');
    }
    
}
