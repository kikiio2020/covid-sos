<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InProgressView extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $sosRequestObjectArray = parent::toArray($request);
        $userId = auth()->user()->id;
        
        return array_merge($sosRequestObjectArray, [
            'sos_text' => $this->resource->sos->name,
            'sos_description' => $this->resource->sos->description,
            'sos_detail_instructions' => $this->resource->sos->detail_instructions,
            'type' => __('model.sos.type.' . $this->resource->sos->type),
            'requester' => $this->resource->user->id == $userId ?
                'Yourself' :
                $this->resource->user->getUserName(),
            'responder' => $this->resource->responded_by ?
                (
                    $this->resource->responded_by == $userId ?
                        'Yourself' :
                        $this->resource->responder->getUserName()
                ) : '', 
            'status_txt' => __('model.ask.status.' . $sosRequestObjectArray['status']),
        ]);
    }
}
