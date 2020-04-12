<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Item extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $objArray = parent::toArray($request);
        
        return array_merge(
            [
                'value' => $objArray['id'], 
                'text' => $objArray['name'],
            ],
            $objArray
        );
    }
}
