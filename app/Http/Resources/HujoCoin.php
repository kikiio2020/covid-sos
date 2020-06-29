<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HujoCoin extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $hujoCoinArray = parent::toArray($request);
       
        return array_merge($hujoCoinArray, [
            'created_date' => date("Y-m-d", strtotime($this->resource->created_at))    
        ]);
    }
}
