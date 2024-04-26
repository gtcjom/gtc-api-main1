<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ConsignmentOrderLocationResource extends JsonResource
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
            'location' => $this->location,
            'items' => ConsignmentOrderDetailResource::collection($this->items),
            'health_unit' => HealthUnitResource::make($this->healthUnit)
        ];
    }
}
