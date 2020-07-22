<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sos extends Model
{
    public const TYPE_GROCERY = 0;
    public const TYPE_SKILL_SHARE = 1;
    public const TYPE_CHORE = 2;
    
    protected $table = 'sos';
    
    protected $fillable = [
        'name',
        'type',
        'description', 
        'detail_instructions', 
        'needed_by', 
        'created_by',
    ];
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function responder()
    {
        return $this->belongsTo(User::class, 'responded_by');
    }
    
    public function shoplistItem()
    {
        return $this->hasMany(ShoplistItem::class, 'sos_id', 'id');
    }
    
    public function sosRequests()
    {
        return $this->hasMany(SosRequest::class, 'sos_id', 'id');
    }
    
    public static function getTypesArray(): array
    {
        return array_map(
            function($item){
                return [
                    'value' => $item,
                    'text' => __('model.sos.type.' . $item),
                ];
            },
            [
                self::TYPE_GROCERY,
                self::TYPE_SKILL_SHARE,
                self::TYPE_CHORE,
            ]
            );
    }
}
