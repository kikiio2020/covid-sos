<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shoplist extends Model
{
    protected $table = 'shoplists';
    
    protected $fillable = [
        'name',
        'description',
        'vendor_name',
        'vendor_address',
        'user_id',
    ];
    
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    
    public function shoplistItem()
    {
        return $this->hasMany(ShoplistItem::class, 'list_id');
    }
    
}
