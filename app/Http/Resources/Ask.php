<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Ask extends JsonResource
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
         
        return array_merge($askObjectArray, [
            'sos_text' => $this->resource->sos->name,
            'status_txt' => __('model.ask.status.' . $askObjectArray['status']),
        ]);
         
    }
}
