<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HistoryView extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $askObjectArray = parent::toArray($request);
        
        $userId = auth()->user()->id;
        
        return array_merge($askObjectArray, [
            'sos_text' => $this->resource->sos->name,
            'sos_description' => $this->resource->sos->description,
            'vendor' => $this->resource->sos->vendor,
            'vendor_address' => $this->resource->sos->vendor_address,
            'delivery_option' => __('model.sos.delivery_option.' . $this->resource->sos->delivery_option),
            'payment_option' => __('model.sos.payment_option.' . $this->resource->sos->payment_option),
            'requester' => $this->resource->user->id == $userId ?
                'Yourself' :
                $this->resource->user->getUserName(),
            'responder' => $this->resource->responded_by == $userId ?
                'Yourself' :
                $this->resource->responder->getUserName(), 
            'status_txt' => __('model.ask.status.' . $askObjectArray['status']),
            'fulfilled_date' => max($this->resource->user_approved, $this->resource->responder_approved),
        ]);
    }
}
