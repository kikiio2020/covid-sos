<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SosRequest extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $userId = auth()->user()->id;
        $sosRequestObjectArray = parent::toArray($request);
        
        $responderName = '';
        $hujoResponder = null;
        if ($this->resource->responder) {
            if ($this->resource->responder->id === $userId) {
                $responderName = 'Yourself';
            } else {
                $this->resource->responder->loadCount('hujoCoin');
                $responderName = $this->resource->responder->name 
                    . ($this->resource->responder->hujo_coin_count > 0 ? ' (Hujo)' : '');
                $hujoResponder = ($this->resource->responder->hujo_coin_count > 0);
            }
        }
        
        return array_merge($sosRequestObjectArray, [
            'sos_text' => $this->resource->sos->name,
            'status_txt' => __('model.sos_request.status.' . $sosRequestObjectArray['status']),
            'hujo_responder' => $hujoResponder,
            'responder_name' => $responderName,
        ]);
         
    }
}
