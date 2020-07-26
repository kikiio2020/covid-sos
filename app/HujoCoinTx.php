<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HujoCoinTx extends Model
{
    protected $fillable = [
        'user_id',
        'recipient_id',
        'function',
        'reference_id',
        'transaction_hash',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
