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
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
