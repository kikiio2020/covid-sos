<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShoplistItem extends Model
{
    protected $fillable = [
        'sos_id',
        'item_id',
        'description',
        'quantity',
        'max_dollar',
    ];
    
    public function sos()
    {
        return $this->belongsTo(Sos::class, 'sos_id');
    }
    
    public function item()
    {
        return $this->hasOne(Item::class, 'id', 'item_id');
    }
}
