<?php

namespace App\Http\Resources;

use App\Models\ConsignmentOrderLocation;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemInventoryResource extends JsonResource
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
            'item_id' => $this->item_id,
            'location_type' => $this->location_type,
            'location_id' => $this->location_id,
            'quantity' => $this->quantity,
            'price' => $this->price,
            // 'created_at' => Carbon::parse($this->datetime)->format('Y-m-d'),
            'item' => ItemResource::make($this->item),

            // 'location' => ConsignmentOrderLocation::make($this->location),
        ];
    }
}
