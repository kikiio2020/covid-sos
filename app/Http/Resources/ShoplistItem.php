<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShoplistItem extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $shoplistItemObjectAr = parent::toArray($request);
        
        return array_merge($shoplistItemObjectAr,
        [
            'item' => $this->resource->item()->first()->name,
        ]);
    }
}
