<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sos extends Model
{
    public const PAYMENT_OPTION_PAID_ONLINE = 0;
    public const PAYMENT_OPTION_CASH = 1;
    public const PAYMENT_OPTION_OTHERS = 2;
    public const PAYMENT_OPTION_NOT_APPLICABLE = 3;
    
    public const DELIVARY_OPTION_DOORFRONT = 0;
    public const DELIVARY_OPTION_INTERCOM = 1;
    public const DELIVARY_OPTION_OTHERS = 2;
    
    public const STATUS_PENDING = 0;
    public const STATUS_IN_PROGRESS = 1;
    public const STATUS_COMPLETED = 2;
    
    protected $table = 'sos';
    
    protected $fillable = [
        'name', 
        'description', 
        'delivery_option', 
        //'delivery_instruction', 
        'payment_option', 
        //'payment_other_description', 
        'other_instruction', 
        //'total_amount', 
        'needed_by', 
        'vendor_name', 
        'vendor_address',
        'created_by',
    ];
    
    public function creator()
    {
        return $this->belongsTo('App\User', 'created_by');
    }
    
    public function responder()
    {
        return $this->belongsTo('App\User', 'responded_by');
    }
    
    public static function getDeliveryOptionsArray(): array
    {
        return array_map(
            function($item){
                return [
                    'value' => $item,
                    'text' => __('model.sos.delivery_option.' . $item),
                ];    
            }, 
            [
                self::DELIVARY_OPTION_DOORFRONT,
                self::DELIVARY_OPTION_INTERCOM,
                self::DELIVARY_OPTION_OTHERS,
            ]
        );
    }
    
    public static function getPaymentOptionsArray(): array
    {
        return array_map(
            function($item){
                return [
                    'value' => $item,
                    'text' => __('model.sos.payment_option.' . $item),
                ];
            },
            [
                self::PAYMENT_OPTION_PAID_ONLINE,
                self::PAYMENT_OPTION_CASH,
                self::PAYMENT_OPTION_OTHERS,
                self::PAYMENT_OPTION_NOT_APPLICABLE,
            ]
        );
    }
    
    
}
