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
            'type_caption' => __('model.sos.type.' . $sosObjectArray['type']),
        ]);
    }
}
