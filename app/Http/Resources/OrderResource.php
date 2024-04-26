<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'clinic' => ClinicResources::make($this->whenLoaded('clinic')),
            'to_clinic' => ClinicResources::make($this->whenLoaded('to_clinic')),
            'order_by' => UserResource::make($this->whenLoaded('order_by')),
            'accepted_by' => UserResource::make($this->whenLoaded('accepted_by')),
            'accepted_at' => $this->accepted_at,
            'details' => OrderDetailsResource::collection($this->whenLoaded('details')),

        ];
    }
}
