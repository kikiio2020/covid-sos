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
        
        return array_merge($sosRequestObjectArray, [
            'sos_text' => $this->resource->sos->name,
            'status_txt' => __('model.sos_request.status.' . $sosRequestObjectArray['status']),
            'responder_name' => $this->resource->responder 
            ?
            (
                $this->resource->responder->id === $userId 
                ?
                'Yourself' :
                $this->resource->responder->name
            ) : '', 
        ]);
         
    }
}
