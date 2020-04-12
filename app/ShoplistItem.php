<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShoplistItem extends Model
{
    protected $fillable = [
        'list_id',
        'item_id',
        'description',
        'quantity',
        'max_dollar',
    ];
    
    public function shoplist()
    {
        return $this->belongsTo(Shoplist::class, 'list_id', 'id');
    }
    
    public function item()
    {
        return $this->hasOne(Item::class, 'id', 'item_id');
    }
}
