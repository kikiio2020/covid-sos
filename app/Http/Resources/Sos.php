<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Sos extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $sosObjectArray = parent::toArray($request);
        
        return array_merge($sosObjectArray, [
            'status_txt' => __('model.sos.status.' . $sosObjectArray['status']),
            'delivery_option_txt' => __('model.sos.delivery_option.' . $sosObjectArray['delivery_option']),
            'payment_option_txt' => __('model.sos.payment_option.' . $sosObjectArray['payment_option']),
        ]);
    }
}
