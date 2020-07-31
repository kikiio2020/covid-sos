<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NearbyView extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return array_merge((array) $this->resource, [
            'distance_km' => round($this->resource->distance / 1000, 2),
            'creator' => $this->resource->{'creator.name'} . ' @ ' . $this->resource->{'creator.address'},
            'type' => __('model.sos.type.' . $this->resource->status),
        ]);
    }
}
